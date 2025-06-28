<?

namespace App\Http\Controllers;

use App\Models\CategorySavings;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Request;

class SavingsController {
    public function deleteSavingsCategory(CategorySavings $category)
    {
        $category->delete();
        return Response::json();
    }

    public function updateSavingsCategory(CategorySavings $category, Request $request)
    {
        $category->update([
            $request->input('field') => trim($request->input('value'))
        ]);
        $category->save();
        return Response::json();
    }

    public function addSavingsCategory(Request $request) 
    {
        $category = CategorySavings::create([
            'title' => trim($request->input('title')), 
            'str_id' => trim($request->input('str_id')), 
            'limit' => trim($request->input('limit')), 
            'currency_id' => $request->input('currency'), 
            'desc' => trim($request->input('desc'))
        ]);
        $category->save();
        return Response::json($category);
    }

    public function getCategories() 
    {
        return Response::json(CategorySavings::withTrashed()->get());
    }
}