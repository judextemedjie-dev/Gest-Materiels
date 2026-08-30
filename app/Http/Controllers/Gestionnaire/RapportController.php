<?php
namespace App\Http\Controllers\Gestionnaire;

use App\Http\Controllers\Controller;
use App\Models\{Materiel, Affectation, Client, Categorie};
use App\Exports\InventaireExport;
use App\Exports\AffectationsExport;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class RapportController extends Controller
{
    public function index()
    {
        return view('gestionnaire.rapports.index');
    }

    public function inventaire(Request $request)
    {
        $query = Materiel::with('categorie');

        if ($request->filled('categorie_id')) {
            $query->where('categorie_id', $request->categorie_id);
        }
        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        $materiels  = $query->orderBy('designation')->get();
        $categories = Categorie::orderBy('nom')->get();
        $total      = $materiels->sum('quantite_stock');

        return view('gestionnaire.rapports.inventaire', compact('materiels', 'categories', 'total'));
    }

    public function affectations(Request $request)
    {
        $query = Affectation::with(['materiel', 'client'])
            ->where('gestionnaire_id', Auth::id());

        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }
        if ($request->filled('date_debut')) {
            $query->where('date_affectation', '>=', $request->date_debut);
        }
        if ($request->filled('date_fin')) {
            $query->where('date_affectation', '<=', $request->date_fin);
        }

        $affectations = $query->orderByDesc('date_affectation')->get();
        $clients      = Client::where('created_by', Auth::id())->orderBy('nom')->get();

        return view('gestionnaire.rapports.affectations', compact('affectations', 'clients'));
    }

    public function exportPdf(Request $request, string $type)
    {
        if ($type === 'inventaire') {
            $materiels = Materiel::with('categorie')->orderBy('designation')->get();
            $pdf = Pdf::loadView('gestionnaire.rapports.pdf.inventaire', compact('materiels'));
            return $pdf->download('inventaire_' . date('Y-m-d') . '.pdf');
        }

        if ($type === 'affectations') {
            $affectations = Affectation::with(['materiel', 'client'])
                ->where('gestionnaire_id', Auth::id())
                ->orderByDesc('date_affectation')->get();
            $pdf = Pdf::loadView('gestionnaire.rapports.pdf.affectations', compact('affectations'));
            return $pdf->download('affectations_' . date('Y-m-d') . '.pdf');
        }

        abort(404);
    }

    public function exportExcel(Request $request, string $type)
    {
        if ($type === 'inventaire') {
            return Excel::download(new InventaireExport(), 'inventaire_' . date('Y-m-d') . '.xlsx');
        }
        if ($type === 'affectations') {
            return Excel::download(new AffectationsExport(Auth::id()), 'affectations_' . date('Y-m-d') . '.xlsx');
        }
        abort(404);
    }
}