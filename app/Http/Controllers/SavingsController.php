<?

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
            $request->input('field') => $request->input('value')
        ]);
        $category->save();
        return Response::json();
    }

    public function addSavingsCategory(Request $request) {
        return Response::json();
    }
}