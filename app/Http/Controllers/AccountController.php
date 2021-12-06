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

        $accounts = $client->accounts()->with('client')->paginate(10);

        return view('accounts.my', compact('accounts'));
    }
}
