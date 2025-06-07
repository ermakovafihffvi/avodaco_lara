<?

use App\Models\CategorySavings;
use Illuminate\Support\Facades\Response;

class SavingsController {
    public function deleteSavingsCategory(CategorySavings $category)
    {
        $category->delete();
        return Response::json();
    }
}