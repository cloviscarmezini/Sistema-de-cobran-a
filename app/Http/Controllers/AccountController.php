<?php

namespace App\Http\Controllers;

use App\Mail\NewAccountMail;
use App\Models\Account;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class AccountController extends Controller
{
    private $account;
    private $client;

    public function __construct(Account $account, Client $client)
    {
        $this->account = $account;
        $this->client = $client;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = auth()->user();

        if($user->role === 'manager') {
            $accounts = $user->accounts()->with('client')->paginate(10);
        } else {
            $accounts = $this->account->paginate(10);
        }

        $accounts = $this->getAccountsStatus($accounts);

        return view('accounts.index', compact('accounts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $clients = $this->client->all();

        return view('accounts.create', compact('clients'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();

        $data['user_id'] = auth()->user()->id;

        $account = $this->account->create($data);

        if(env('APP_ENV') === 'production') {
            Mail::to($account->client->email)->send(new NewAccountMail($account));
        }


        flash('Título adicionado com sucesso')->success();

        return redirect()->route('accounts.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $account = $this->account->find($id);
        $clients = $this->client->all();

        return view('accounts.edit', compact('account', 'clients'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = $request->all();

        $account = $this->account->find($id);

        $account->update($data);

        flash('Título atualizado com sucesso')->success();

        return redirect()->route('accounts.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $account = $this->account->find($id);
        $account->delete();

        flash('Título deletado com sucesso')->success();
        return redirect()->route('accounts.index');
    }

    public function my()
    {
        $client = auth()->user();

        $accounts = $client
        ->accounts()
        ->with('tradeInstallments')
        ->paginate(10);

        $accounts = $this->getAccountsStatus($accounts);

        return view('accounts.my', compact('accounts'));
    }

    public function trade($account_id)
    {
        $client = auth()->user();

        $account = $client
        ->accounts()
        ->where('id', $account_id)
        ->with('tradeInstallments')
        ->first();

        return view('accounts.trade', compact('account'));
    }

    public function installments($account_id)
    {
        $client = auth()->user();

        $account = $client
        ->accounts()
        ->where('id', $account_id)
        ->with(['tradeInstallments' => function($query) {
            $query->orderBy('expiration_date', 'ASC');
        }])
        ->first();

        return view('accounts.installments', compact('account'));
    }

    public function view($account_id)
    {
        $account = $this->account
        ->find($account_id);
        $account->tradeInstallments;

        return view('accounts.view', compact('account'));
    }

    private function getAccountsStatus($accounts)
    {
        foreach($accounts as $idx=>$account) {
            $status = $this->getAccountStatus($account);

            $accounts[$idx]['status_description'] = $status['status_description'];
            $accounts[$idx]['status'] = $status['status'];
        }

        return $accounts;
    }

    private function getAccountStatus($account)
    {
        if($account->tradeInstallments()->count()) {
            $last_payment = $account->tradeInstallments()->orderBy('expiration_date', 'desc')->first();

            $response = [
                'status_description' => '',
                'status' => ''
            ];

            if($last_payment->status) {
                $response['status_description'] = '<span class="badge rounded-pill bg-success text-light">Pago</span>';
                $response['status'] = 1;
            } else {
                $last_installment_paid = $account->tradeInstallments()->whereStatus(0)->orderBy('expiration_date')->first();
                $response['status_description'] = "<span class='badge rounded-pill bg-primary text-light'>Aguardando {$last_installment_paid->installment}ª parcela</span>";
                $response['status'] = 2;
            }
        } else if($account->expiration_date < date('Y-m-d')) {
            $response['status_description'] = '<span class="badge rounded-pill bg-danger text-light">Vencido</span>';
            $response['status'] = 3;
        } else {
            $response['status_description'] = '<span class="badge rounded-pill bg-warning">Aguardando negociação</span>';
            $response['status'] = 4;
        }

        return $response;
    }

    public function doTrade(Request $request, $id)
    {
        $installments = $request->installments;

        $account = $this->account->find($id);

        $value = number_format(($account->value / $installments), 2, '.', '');

        $date = \Carbon\Carbon::now();

        foreach(range(1, $installments) as $installment) {
            \App\Models\AccountInstallment::create([
                'account_id' => $id,
                'value' => $value,
                'installment' => $installment,
                'expiration_date' => $date->addMonth(),
                'status' => 0
            ]);
        }

        flash('Negociação realizada com sucesso')->success();

        return redirect()->route('accounts.my');
    }

    public function payInstallment($id)
    {
        $installment = \App\Models\AccountInstallment::find($id);

        $account = $this->account->find($installment->account_id);

        $last_installment_paid = $account->tradeInstallments()->whereStatus(0)->orderBy('expiration_date')->first();

        if($last_installment_paid->installment != $installment->installment) {
            flash("Realize o pagamento da {$last_installment_paid->installment}ª parcela antes de efetuar o pagamento da {$installment->installment}ª parcela.")->error();
            return redirect()->route('accounts.installments', ['account_id' => $installment->account_id]);
        }

        $installment->update([
            'status' => 1
        ]);

        flash('Parcela paga com sucesso')->success();

        return redirect()->route('accounts.installments', ['account_id' => $installment->account_id]);
    }

    //import Accounts 
    public function import()
    {
        return view('accounts.import');
    }

    public function import_upload(Request $request)
    {
        $user = auth()->user();
        
        $fileName = $request->file->getClientOriginalName();

        //Where uploaded file will be stored on the server 
        $location = 'uploads'; //Created an "uploads" folder for that

        try {
            // Upload file
            $request->file->move($location, $fileName);
        
            // In case the uploaded file path is to be stored in the database 
            $filepath = public_path($location . "/" . $fileName);

            // Reading file
            $file = fopen($filepath, "r");
            $import_data_arr = []; // Read through the file and store the contents as an array
            $i = 0;
            //Read the contents of the uploaded file 
            while (($filedata = fgetcsv($file, 1000, ",")) !== FALSE) {
                $num = count($filedata);
                for ($c = 0; $c < $num; $c++) {
                    $import_data_arr[$i][] = $filedata[$c];
                }
                $i++;
            }
            fclose($file); //Close after reading$j = 0;

            foreach ($import_data_arr as $importData) {
                $client_email  = $importData[0];         //Document
                $description = $importData[1];       //Descricao
                $type  = $importData[2];             //Tipo
                $readjustment_type = $importData[3]; //Rejuste
                $value = $importData[4];             //valor
                $discount= $importData[5];           //disconto
                $installments= $importData[6];       //parcela
                $expiration_date  = $importData[7];  //data           
                
                    
                $client = $this->client->where('email',$client_email)->first();
                
                if(!$client) { 
                  abort(403, "Cliente não encontrado!");
                }
            
                $expiration_date = \Carbon\Carbon::createFromFormat("d/m/Y", trim($expiration_date));
                $expiration_date = $expiration_date->format('Y-m-d'); 
                
                $data['user_id'] = $user->id;
                $data['client_id'] = $client->id;
                $data['description'] = $description;
                $data['type'] = $type; 
                $data['readjustment_type'] = $readjustment_type;
                $data['value'] = $value;
                $data['discount'] = $discount;
                $data['installments'] = $installments;
                $data['expiration_date'] = $expiration_date;    
        
                $account = $this->account->create($data);
        
                if(env('APP_ENV') === 'production') {
                    Mail::to($account->client->email)->send(new NewAccountMail($account));
                }
            } 
            flash('Título importado com sucesso')->success();
        } catch (\Exception $e) {
            flash($e->getMessage())->error();
        }
            
        return redirect()->route('accounts.index');

    }

}
