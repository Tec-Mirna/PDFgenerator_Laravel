<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Payments;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\File; // tocar fill sisten de laravel

class PaymentsController extends Controller
{
    // En lavarel se le llama store a la funcion de guardar(create)
    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'fullname' => 'required',
            'email'  => 'required|email',
            'card_number'  => 'required',
            'card_exp_month' => 'required',
            'card_exp_year' => 'required',
            'card_cvc' => 'required',
            'amount' => 'required',
        ]);
        if($validator->fails()){
            return response()->json([
                 'status' => false,
                 'message' => 'Validation errors',
                 'data' => $validator->errors() //ver detallado el error
            ], 401);
        }

        $payment = Payments::create($request->all());
        // esta variable se pasa al compact como un string
        
        // Creación y gestión de pdf
        // dentro de una carpeta llamada pdf está un archivo invoice (para renderizar los pdf)
        $pdf = Pdf::loadView('pdf.invoice',  compact('payment')); // loadView: cargar una vista

        // hacer proceso de guardado(nativo de laravel)
        // en la carpeta public_path(no enviar a la nube sino guardar en el proyecto) busca la carpeta invoices y si no encuentra la crea
        if(!File::isDirectory(public_path('invoices'))){
                            //   directorio a crear       permisos
            File::makeDirectory(public_path('invoices'), 0777, true, true);
        }
        // para descargar
        $pdf->download('invoice.pdf');

        //guardar
        $pdf->save(public_path('invoices/invoce.pdf'));


        return response()->json([
            'status' => true,
            'message' => 'New payment created',
            'data' => $payment,
        ], 201);
    }
}
