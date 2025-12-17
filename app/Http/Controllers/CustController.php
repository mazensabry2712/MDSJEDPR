<?php

namespace App\Http\Controllers;

use App\Models\Cust;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
class CustController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $custs=cust::all() ;
        return view("dashboard.customer.index",compact("custs"));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('dashboard.customer.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'abb' => 'nullable|string|max:255'??null,
            'tybe' => 'nullable|in:GOV,PRIVATE'??null,
            'logo' => 'nullable|file|mimes:jpeg,jpg,png,gif,webp|max:2048',
            'customercontactname' => 'nullable|string|max:255'??null,
            'customercontactposition' => 'nullable|string|max:255'??null,
            'email' => 'nullable|email|max:255'??null,
            'phone' => 'nullable|string|max:15'??null,
        ], [
            'name.required' => 'Customer name is required',
            'email.email' => 'Please enter a valid email address',
            'logo.mimes' => 'Logo must be an image file (jpeg, jpg, png, gif, webp)',
            'logo.max' => 'Logo file size cannot exceed 2MB',
        ]);

        $data = $request->except(['logo']);

      if($request->file('logo')){
        $file = $request->file('logo');
        $fileName = time() . '_' . $file->getClientOriginalName();

        // حفظ في مجلد storge الأصلي
        $destinationPath = base_path('storge');
        $file->move($destinationPath, $fileName);

        // نسخ إلى مجلد public للعرض
        $publicPath = public_path('storge');
        copy(base_path('storge/' . $fileName), $publicPath . '/' . $fileName);

        $data['logo'] = 'storge/' . $fileName;
        }

// إذا كانت البيانات سليمة، يتم حفظها
Cust::create($data);

// إضافة رسالة النجاح
session()->flash('Add', 'Customer registration successful');

//return back(); // أو
return redirect('/customer'); //حسب الحاجة


    }

    /**
     * Display the specified resource.
     */
    public function show(Cust $customer)
    {
        // Get customer order/position number
        $customers = Cust::orderBy('id')->get();
        $loop_index = $customers->search(function($item) use ($customer) {
            return $item->id === $customer->id;
        }) + 1;

        return view('dashboard.customer.show', compact('customer', 'loop_index'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Cust $customer)
    {
        return view('dashboard.customer.edit', compact('customer'));
    }

    /**
     * Update the specified resource in storage.
     */

     public function update(Request $request, Cust $customer)
     {
         $validated = $request->validate([
            'name' => 'required|string|max:255',
            'abb' => 'nullable|string|max:255',
            'tybe' => 'nullable|in:GOV,PRIVATE',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'customercontactname' => 'nullable|string|max:255',
            'customercontactposition' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:15',
         ], [
            'name.required' => 'Customer name is required',
            'email.email' => 'Please enter a valid email address',
            'logo.image' => 'Logo must be an image file',
            'logo.mimes' => 'Logo must be an image file (jpeg, jpg, png, gif, webp)',
            'logo.max' => 'Logo file size cannot exceed 2MB',
         ]);

         $old_logo = $customer->logo;
         $data = $request->except(['logo']);

         if($request->file('logo')){
             // حذف الصورة القديمة إذا كانت موجودة من كلا المكانين
             if($old_logo && file_exists(base_path($old_logo))) {
                 unlink(base_path($old_logo));
             }
             if($old_logo && file_exists(public_path($old_logo))) {
                 unlink(public_path($old_logo));
             }

             $file = $request->file('logo');
             $fileName = time() . '_' . $file->getClientOriginalName();

             // حفظ في مجلد storge الأصلي
             $destinationPath = base_path('storge');
             $file->move($destinationPath, $fileName);

             // نسخ إلى مجلد public للعرض
             $publicPath = public_path('storge');
             copy(base_path('storge/' . $fileName), $publicPath . '/' . $fileName);

             $data['logo'] = 'storge/' . $fileName;
         }

         $customer->update($data);
         session()->flash('Add', 'Registration successful.');
         return redirect('/customer');
     }














    // public function update(Request $request)
    // {



    //     $id = $request->id;

    //     // التحقق من صحة البيانات
    //     $this->validate($request, [
    //         'name' => 'required|string|max:255'.$id,
    //         'abb' => 'nullable|string|max:255',
    //         'tybe' => 'nullable|in:GOV,PRIVATE',
    //          'logo' => 'nullable|file|mimetypes:image/jpeg,image/png,image/gif,image/webp,application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,image/svg+xml|max:2048',
    //         'customercontactname' => 'nullable|string|max:255',
    //         'customercontactposition' => 'nullable|string|max:255',
    //         'email' => 'nullable|email|max:255',
    //         'phone' => 'nullable|string|max:15',
    //     ]);

    //     // العثور على العنصر وتحديث
    //     $aams = Cust::findOrFail($id); // نستخدم findOrFail للتأكد من وجود العنصر
    //     $aams->update([
    //         'name' => $request->name,
    //         'abb' => $request->abb,
    //         'tybe' => $request->tybe,
    //         'logo' => $request->logo,
    //         'customercontactname' => $request->customercontactname,
    //         'customercontactposition' => $request->customercontactposition,
    //         'email' => $request->email,
    //         'phone' => $request->phone,
    //     ]);

    //     // رسالة تأكيد وإعادة توجيه
    //     session()->flash('edit', 'The section has been successfully modified');
    //     return redirect('/customer'); // إعادة التوجيه إلى صفحة الأقسام











    // }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Cust $customer)
    {
        // حذف الصورة إذا كانت موجودة من كلا المكانين
        if($customer->logo) {
            if(file_exists(base_path($customer->logo))) {
                unlink(base_path($customer->logo));
            }
            if(file_exists(public_path($customer->logo))) {
                unlink(public_path($customer->logo));
            }
        }

        $customer->delete();
        session()->flash('delete', 'Deleted successfully');
        return redirect('/customer');
    }

    /**
     * Export Customers to PDF - Table Layout
     */
    public function exportPDF()
    {
        $customers = Cust::all();

        if ($customers->isEmpty()) {
            return redirect()->back()->with('error', 'No customers found to export.');
        }

        // Create PDF - Portrait A4
        $pdf = new \TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);

        // Set document info
        $pdf->SetCreator('MDSJEDPR');
        $pdf->SetTitle('Customers Management - MDSJEDPR');
        $pdf->SetSubject('Customers Report');

        // Remove default header/footer
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);

        // Set margins
        $pdf->SetMargins(10, 10, 10);
        $pdf->SetAutoPageBreak(true, 15);

        // Add a page
        $pdf->AddPage();

        // System Name
        $pdf->SetFont('helvetica', 'B', 18);
        $pdf->SetTextColor(103, 126, 234);
        $pdf->Cell(0, 8, 'MDSJEDPR', 0, 1, 'C');

        // Title
        $pdf->SetFont('helvetica', 'B', 14);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->Cell(0, 7, 'Customers Management', 0, 1, 'C');

        // Date
        $pdf->SetFont('helvetica', '', 9);
        $pdf->SetTextColor(100, 100, 100);
        $pdf->Cell(0, 5, 'Generated: ' . date('m/d/Y, g:i:s A'), 0, 1, 'C');
        $pdf->Ln(3);

        // Table header
        $pdf->SetFillColor(103, 126, 234); // Blue background
        $pdf->SetTextColor(255, 255, 255); // White text
        $pdf->SetFont('helvetica', 'B', 9);

        // Column widths for Portrait A4 (total ~190mm) - Reduced sizes for Portrait and increased Email width
        $w = array(6, 25, 15, 15, 10, 28, 28, 40, 23);        // Header row
        $pdf->Cell($w[0], 7, '#', 1, 0, 'C', true);
        $pdf->Cell($w[1], 7, 'Name', 1, 0, 'C', true);
        $pdf->Cell($w[2], 7, 'Abb', 1, 0, 'C', true);
        $pdf->Cell($w[3], 7, 'Type', 1, 0, 'C', true);
        $pdf->Cell($w[4], 7, 'Logo', 1, 0, 'C', true);
        $pdf->Cell($w[5], 7, 'Contact Name', 1, 0, 'C', true);
        $pdf->Cell($w[6], 7, 'Contact Position', 1, 0, 'C', true);
        $pdf->Cell($w[7], 7, 'Email', 1, 0, 'C', true);
        $pdf->Cell($w[8], 7, 'Phone', 1, 1, 'C', true);

        // Reset text color for data rows
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetFont('helvetica', '', 8);

        // Data rows
        $fill = false;
        foreach ($customers as $index => $customer) {
            $pdf->SetFillColor(245, 245, 245);

            // Check if logo exists
            $hasLogo = 'No';
            if (!empty($customer->logo) && file_exists(public_path($customer->logo))) {
                $hasLogo = 'Yes';
            }

            $pdf->Cell($w[0], 6, ($index + 1), 1, 0, 'C', $fill);
            $pdf->Cell($w[1], 6, $customer->name ?? '', 1, 0, 'L', $fill);
            $pdf->Cell($w[2], 6, $customer->abb ?? '', 1, 0, 'L', $fill);
            $pdf->Cell($w[3], 6, $customer->tybe ?? '', 1, 0, 'L', $fill);
            $pdf->Cell($w[4], 6, $hasLogo, 1, 0, 'C', $fill); // Yes/No for logo
            $pdf->Cell($w[5], 6, $customer->customercontactname ?? '', 1, 0, 'L', $fill);
            $pdf->Cell($w[6], 6, $customer->customercontactposition ?? '', 1, 0, 'L', $fill);
            $pdf->Cell($w[7], 6, $customer->email ?? '', 1, 0, 'L', $fill);
            $pdf->Cell($w[8], 6, $customer->phone ?? '', 1, 1, 'L', $fill);

            $fill = !$fill;
        }

        // Output PDF
        $pdf->Output('Customers_Management_' . time() . '.pdf', 'D');
        exit;
    }    /**
     * Print Customers View - Same design as PDF for direct printing
     */
    public function printView()
    {
        try {
            // Get all customers
            $customers = Cust::all();

            return view('dashboard.customer.print', compact('customers'));

        } catch (Exception $e) {
            Log::error('Customers print view error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error loading print view: ' . $e->getMessage());
        }
    }
}
