<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RifaController extends Controller
{
    public function MostrarBoletos(Request $request)
    {
        $esJefe = $request->input('esJefe');
        $bRifa = $request->input('bRifa');
        $boletosArray = [];
        for ($i = 1; $i <= 200; $i++) {
            $boletosArray[$i] = [
                'BoletoID' => null,
                'numero' => $i,
                'Nombre' => null,
                'Rifar' => 0,
                'PendientePago' => 0
            ];
        }

        if ($esJefe == 1 && $bRifa == 0) {
            $numerosBD = DB::select('SELECT "Numero", "BoletoID", "PendientePago", "Nombre" FROM "Boletos" WHERE "Rifar" = 1 AND ("Gano" != 0 OR "Gano" IS NULL)');
            foreach ($numerosBD as $numero) {
                if (isset($boletosArray[$numero->Numero])) {
                    $boletosArray[$numero->Numero]['BoletoID'] = $numero->BoletoID;
                    $boletosArray[$numero->Numero]['Rifar'] = 1;
                    $boletosArray[$numero->Numero]['PendientePago'] = $numero->PendientePago;
                    $boletosArray[$numero->Numero]['Nombre'] = $numero->Nombre;
                }
            }
        } else if ($esJefe == 1 && $bRifa == 1) {
            $numerosBD = DB::select('SELECT "Numero", "BoletoID", "Nombre" FROM "Boletos" WHERE "Rifar" = 1 AND "PendientePago" = 1 AND ("Gano" != 0 OR "Gano" IS NULL)');
            $boletosArray = [];
            foreach ($numerosBD as $numero) {
                $boletosArray[$numero->Numero] = [
                    'BoletoID' => $numero->BoletoID,
                    'numero' => $numero->Numero,
                    'Rifar' => 1,
                    'PendientePago' => 1,
                    'Nombre' => $numero->Nombre
                ];
            }
        } else if ($esJefe == 0) {
            $numerosBD = DB::select('SELECT "Numero", "BoletoID", "PendientePago" FROM "Boletos" WHERE "Rifar" = 1 AND ("Gano" != 0 OR "Gano" IS NULL)');
            if (empty($numerosBD)) {
                foreach ($numerosBD as $numero) {
                    $boletosArray[$numero->Numero]['BoletoID'] = $numero->BoletoID;
                    $boletosArray[$numero->Numero]['Rifar'] = null;
                    $boletosArray[$numero->Numero]['PendientePago'] = null;
                }
            } else {
                foreach ($numerosBD as $numero) {
                    if (isset($boletosArray[$numero->Numero])) {
                        $boletosArray[$numero->Numero]['BoletoID'] = $numero->BoletoID;
                        $boletosArray[$numero->Numero]['Rifar'] = 1;
                        $boletosArray[$numero->Numero]['PendientePago'] = $numero->PendientePago;
                    }
                }
            }


        }
        return json_encode($boletosArray);
    }
    public function GuardarBoletos(Request $request)
    {
        $NumeroBoleto = $request->input('NumeroBoleto');
        $Nombre = $request->input('Nombre');
        try {
            $numerosBD = DB::select('SELECT "BoletoID" FROM "Boletos" WHERE "Numero" = ?', [$NumeroBoleto]);
            if (empty($numerosBD)) {
                DB::insert(
                    'INSERT INTO "Boletos" ("Numero", "Nombre", "PendientePago", "Rifar", "FechaRegistro") VALUES (?, ?, ?, ?, ?)',
                    [$NumeroBoleto, $Nombre, 0, 1, now()]
                );
            } else {
                return response()->json([], 500);
            }

        } catch (\Throwable $th) {
            return response()->json([], 500);
        }
    }
    public function EditarBoleto(Request $request)
    {
        $BoletoID = $request->input('BoletoID');
        $ConfirmacionPago = $request->input('ConfirmacionPago');
        try {
            if ($ConfirmacionPago == 1) {
                DB::update(
                    'UPDATE "Boletos" SET "PendientePago" = ?, "FechaModificado" = ? WHERE "BoletoID" = ?',
                    [1, now(), $BoletoID]
                );
            } else if ($ConfirmacionPago == 0) {
                DB::delete(
                    'DELETE FROM "Boletos" WHERE "BoletoID" = ?',
                    [$BoletoID]
                );
            }
        } catch (\Throwable $th) {
            return response()->json([], 500);
        }
    }
    public function NoGanadorBoleto(Request $request)
    {
        $BoletoID = $request->input('BoletoID');
        $Gano = $request->input('Gano');
        try {
            DB::update(
                'UPDATE "Boletos" SET "Gano" = ?, "Rifar" = ?, "FechaModificado" = ? WHERE "BoletoID" = ?',
                [$Gano, 0, now(), $BoletoID]
            );
        } catch (\Throwable $th) {
            return response()->json([], 500);
        }
    }
}
