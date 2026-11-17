<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Expense;
use App\Models\Invoice;
use App\Models\Payment;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AccountsController extends Controller
{
    public function paymentsIndex(Request $request): View
    {
        $query = Payment::with(['booking.customer', 'customer', 'agent', 'creator']);

        if ($request->filled('method')) {
            $query->where('payment_method', $request->method);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('payment_number', 'like', "%{$search}%")
                    ->orWhere('transaction_id', 'like', "%{$search}%")
                    ->orWhereHas('customer', function ($cq) use ($search) {
                        $cq->where('name', 'like', "%{$search}%");
                    });
            });
        }

        $payments = $query->orderBy('payment_date', 'desc')->paginate(15)->withQueryString();
        $totalCollected = Payment::where('status', 'completed')->where('type', '!=', 'refund')->sum('amount');
        $totalRefunded = Payment::where('status', 'completed')->where('type', 'refund')->sum('amount');

        return view('admin.accounts.payments', compact('payments', 'totalCollected', 'totalRefunded'));
    }

    public function invoicesIndex(Request $request): View
    {
        $query = Invoice::with(['customer', 'booking.package', 'tax']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('invoice_number', 'like', "%{$search}%")
                ->orWhereHas('customer', function ($cq) use ($search) {
                    $cq->where('name', 'like', "%{$search}%");
                });
        }

        $invoices = $query->orderBy('invoice_date', 'desc')->paginate(15)->withQueryString();
        $totalBilled = Invoice::where('status', '!=', 'cancelled')->sum('total_amount');
        $totalPaid = Invoice::where('status', '!=', 'cancelled')->sum('paid_amount');
        $totalBalance = Invoice::where('status', '!=', 'cancelled')->sum('balance_amount');

        return view('admin.accounts.invoices', compact('invoices', 'totalBilled', 'totalPaid', 'totalBalance'));
    }

    public function invoiceShow(int $id): View
    {
        $invoice = Invoice::with(['customer', 'booking.package', 'payments.creator', 'tax'])->findOrFail($id);

        return view('admin.accounts.invoice_show', compact('invoice'));
    }

    public function downloadInvoicePdf(int $id)
    {
        $invoice = Invoice::with(['customer', 'booking.package', 'payments', 'tax'])->findOrFail($id);
        $pdf = Pdf::loadView('pdf.invoice', compact('invoice'));

        return $pdf->download("Tax-Invoice-{$invoice->invoice_number}.pdf");
    }

    public function expensesIndex(Request $request): View
    {
        $query = Expense::with(['branch', 'booking.customer', 'approver']);

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('expense_number', 'like', "%{$search}%")
                ->orWhere('title', 'like', "%{$search}%")
                ->orWhere('paid_to', 'like', "%{$search}%");
        }

        $expenses = $query->orderBy('expense_date', 'desc')->paginate(15)->withQueryString();
        $totalExpenses = Expense::where('status', 'paid')->sum('amount');

        return view('admin.accounts.expenses', compact('expenses', 'totalExpenses'));
    }

    public function createExpense(): View
    {
        $branches = Branch::where('is_active', true)->get();

        return view('admin.accounts.create_expense', compact('branches'));
    }

    public function storeExpense(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'category' => 'required|string|in:office,marketing,supplier,operational,salary,travel,other',
            'title' => 'required|string|max:200',
            'amount' => 'required|numeric|min:1',
            'expense_date' => 'required|date',
            'branch_id' => 'nullable|exists:branches,id',
            'paid_to' => 'nullable|string|max:150',
            'payment_method' => 'required|string|in:cash,bank_transfer,upi,cheque',
            'notes' => 'nullable|string',
        ]);

        $expenseNumber = 'EXP-'.date('Y').'-'.strtoupper(Str::random(6));

        Expense::create(array_merge($validated, [
            'expense_number' => $expenseNumber,
            'status' => 'paid',
            'approved_by' => Auth::id(),
            'created_by' => Auth::id(),
        ]));

        return redirect()->route('admin.accounts.expenses')->with('success', "Expense #{$expenseNumber} recorded.");
    }

    public function gstReport(Request $request): View
    {
        $year = $request->get('year', '2026-2027');
        $invoices = Invoice::where('financial_year', $year)->where('status', '!=', 'cancelled')->get();

        $taxableTotal = $invoices->sum('subtotal');
        $cgstTotal = $invoices->sum('cgst_amount');
        $sgstTotal = $invoices->sum('sgst_amount');
        $igstTotal = $invoices->sum('igst_amount');
        $tcsTotal = $invoices->sum('tcs_amount');
        $grandTotal = $invoices->sum('total_amount');

        return view('admin.accounts.gst_report', compact(
            'year',
            'invoices',
            'taxableTotal',
            'cgstTotal',
            'sgstTotal',
            'igstTotal',
            'tcsTotal',
            'grandTotal'
        ));
    }
}
