<?php

namespace App\Http\Controllers;

use App\Models\DetallePago;
use App\Models\MetodoPago;
use App\Models\PlanPago;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PlanPagoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = PlanPago::with('reserva.cliente');

        if ($request->search) {
            $search = $request->search;
            $query->whereHas('reserva.cliente', function ($q) use ($search) {
                $q->where('nombres', 'like', "%$search%")
                    ->orWhere('email', 'like', "%$search%");
            });
        }

        $planes = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();

        return Inertia::render('PlanPagos/Index', [
            'planes' => $planes,
            'filters' => $request->all(),
        ]);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'reserva_id' => 'required|exists:reservas,id',
            'tipo_pago' => 'required|string|in:CONTADO,CREDITO',
            'total' => 'required|numeric|min:0',
        ]);

        if (PlanPago::where('reserva_id', $request->reserva_id)->exists()) {
            return back()->with('error', 'Ya existe un plan de pago para esta reserva.');
        }

        PlanPago::create([
            'reserva_id' => $request->reserva_id,
            'tipo_pago' => $request->tipo_pago,
            'total' => $request->total,
            'saldo' => $request->total, // El saldo inicia igual que el total
        ]);

        return back()->with('success', 'Plan de pago registrado.');
    }

    /**
     * Display the specified resource.
     */
    public function show(PlanPago $planPago)
    {
        $planPago->load([
            'reserva.detalles.avances', // carga los avances de cada detalle
            'reserva.cliente',
            'detalle_pagos.metodo_pago', // si quieres mostrar el historial de pagos y método
            'reserva.detalles.trabajador',
        ]);

        return Inertia::render('PlanPagos/Show', [
            'planPago' => $planPago,
        ]);
    }



    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PlanPago $planPago)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PlanPago $planPago)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PlanPago $planPago)
    {
        //
    }

    public function pagar(PlanPago $planPago)
    {
        // Cargar historial de pagos y métodos activos
        $planPago->load('detalle_pagos.metodo_pago');

        $metodos = MetodoPago::where('estado', true)->get();

        return Inertia::render('PlanPagos/Pagar', [
            'planPago' => $planPago,
            'metodos' => $metodos,
        ]);
    }

    // Guardar un pago
    public function guardarPago(Request $request, PlanPago $planPago)
    {
        $request->validate([
            'monto' => 'required|numeric|min:0.01|max:' . $planPago->saldo,
            'fecha' => 'required|date',
            'metodo_pago_id' => 'required|exists:metodo_pagos,id',
            'comprobante' => 'nullable|file|mimes:jpg,png,pdf',
        ]);

        $monto = $request->monto;

        // Subir comprobante si existe
        $comprobantePath = $request->file('comprobante') ? $request->file('comprobante')->store('comprobantes') : null;

        // Crear detalle de pago
        DetallePago::create([
            'plan_pago_id' => $planPago->id,
            'fecha' => $request->fecha,
            'monto' => $monto,
            'saldo' => $planPago->saldo - $monto,
            'comprobante' => $comprobantePath,
            'metodo_pago_id' => $request->metodo_pago_id,
        ]);

        // Actualizar saldo del plan
        $planPago->saldo -= $monto;
        $planPago->save();

        return back()->with('success', 'Pago registrado correctamente.');
    }
}
