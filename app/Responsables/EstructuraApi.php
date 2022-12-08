<?php

namespace App\Responsables;

use App\CodigoApi;
use Illuminate\Contracts\Support\Responsable;

class EstructuraApi implements Responsable
{
    private $status   = 'success';
    private $action   = 'next';
    private $show     = true;
    private $message  = '';
    private $delay    = null;
    private $code     = 'SUC-001';
    public  $results  = null;
    public  $debug    = '';


    /* Pasar a base de datos */
    public function __construct()
    {
        //
    }

    public function setEstado($codigo, $estado, $mensaje)
    {
        $this->code     = $codigo;
        $this->status   = $estado;
        $this->message  = $mensaje;
    }

    public function setResultado($resultados)
    {
        $this->results = $resultados;
    }

    public function toResponse($request)
    {

        switch ($this->status) {
            case 'error': {
                    $this->action = 'stop';
                    break;
                }
                /*case 'WARNING': {
                    $this->action = "stop";
                }*/
            case 'info': {
                    $this->action = 'stop';
                    break;
                }
        }

        return [
            "status"    => $this->status,
            "action"    => $this->action,
            "show"      => $this->show,
            "message"   => $this->message,
            "delay"     => $this->delay,
            "code"      => $this->code,
            "results"   => $this->results
        ];
    }
}
