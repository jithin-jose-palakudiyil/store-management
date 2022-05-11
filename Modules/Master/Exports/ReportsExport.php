<?php

namespace Modules\Master\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ReportsExport implements FromView 
{
 
    public function __construct($data,$view_blade,$slug)
    {
        $this->data = $data;
        $this->view_blade = $view_blade;
        $this->slug = $slug;
    }

    
    public function view(): View
    {
        $view_blade = $this->view_blade;
        return view($view_blade, [
            'data' => $this->data, 
            'slug' => $this->slug, 
        ]);
    }
    
     
 
 
}
