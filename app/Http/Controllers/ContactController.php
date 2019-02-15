<?php

namespace App\Http\Controllers;

use App\Contact;
use Illuminate\Http\Request;
use App\Jobs\SendEmail;
use App\NetworkConfig;


class ContactController extends Controller
{

    public function showAll()
    {
        return response()->json(Contact::all());
    }

    public function showOneContact($id)
    {
        return response()->json(Contact::find($id));
    }

    public function create(Request $request)


    {
        $this->validate($request, [
            'name' => 'required',
        ]);
        $contact = Contact::create($request->all());
        return response()->json($contact, 201);
    }

    public function update($id, Request $request)
    {
        $contact = Contact::findOrFail($id);
        $contact->update($request->all());

        return response()->json($contact, 200);
    }

    public function delete($id)
    {
        Contact::findOrFail($id)->delete();
        return response('Deleted Successfully', 200);
    }

    public function contato(Request $request) {
        $data = $request->all();

        //carregar email da rede no escola digital configurações avançadas 
        $email_escola = NetworkConfig::where('id', $request->currentNetwork->id)->pluck('contact_email')->first();
        
        if(empty($email_escola)){
            $email_escola = getenv('MAIL_FROM_ADDRESS');
        }

        //email de retorno avisando que entraremos em contato em breve.
        $email = [
            'from' => $email_escola,
            'to' => $data['email'],
            'subject' => 'Contato – Escola Digital',
            'body' => 'Olá '.$data['nome'].'!<br>Estamos analisando seu email, entraremos em contato com você em breve.<br>Obrigado.'
        ];

        $job = (new SendEmail($email));
        dispatch($job); 

        //email para o email do escola digital informando o contato
        $email = [
            'from' => $data['email'],
            'to' => $email_escola,
            'subject' => 'Contato – Escola Digital',
            'body' => 'Nome:'.$data['nome'].'<br>Email:'.$data['email'].'<br>Assunto:'.$data['assunto'].'<br>Mensagem:'.$data['conteudo']
        ];
            
        $job = (new SendEmail($email));
        dispatch($job); 

        return $this->responseOK("Email enviado!");
    }
}