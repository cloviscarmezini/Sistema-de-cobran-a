<?php

namespace App\Http\Controllers;

use App\Mail\NewAccountMail;
use App\Models\Account;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

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
}
