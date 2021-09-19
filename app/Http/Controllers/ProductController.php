<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Facade\FlareClient\Http\Exceptions\NotFound;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ProductController extends Controller
{
    /**
     * Get Product by ID
     *
     * @param int $id
     * @return JsonResponse
     */
    public function getProductById(int $id): JsonResponse
    {
        $product = Product::findOrFail($id)->first();

        return new JsonResponse($product);
    }

    /**
     * Get Product by Code and Locale
     *
     * @param string $product_code
     * @param string $locale
     * @return JsonResponse
     */
    public function getProductByCodeAndLocale(string $product_code, string $locale): JsonResponse
    {
        $product = Product::where('product_code', $product_code)
            ->where('locale', $locale)
            ->first();

        if (!$product) {
            throw new NotFoundHttpException();
        }

        return new JsonResponse($product);
    }

    /**
     * Create Product
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function createProduct(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'product_name' => ['required', 'nullable', 'max:255', Rule::unique('products')],
                'product_desc' => 'nullable|string|max:255',
                'product_category' => 'nullable|string|max:20',
                'product_price' => 'nullable|numeric',
                'product_code' => 'required|string|max:255',
                'locale' => 'required|string|max:5',
            ]);
        } catch (\Exception $e) {
            $this->throwFriendlyErrors($e);
        }

        $given_request_data = $request->only(
            'product_name',
            'product_desc',
            'product_category',
            'product_price',
            'product_code',
            'locale'
        );

        $product = new Product();
        foreach ($given_request_data as $key => $value) {
            $product->$key = $given_request_data[$key];
        }
        $product->save();

        return new JsonResponse($product);
    }

    /**
     * Update Product
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function updateProductById(Request $request, int $id): JsonResponse
    {
        $product = Product::findOrFail($id)->first();

        try {
            $request->validate([
                'product_name' => ['nullable', 'max:255', Rule::unique('products')->ignoreModel($product)],
                'product_desc' => 'nullable|string|max:255',
                'product_category' => 'nullable|string|max:20',
                'product_price' => 'nullable|numeric',
                'product_code' => 'string|max:255',
                'locale' => 'string|max:5',
            ]);
        } catch (\Exception $e) {
            $this->throwFriendlyErrors($e);
        }

        $given_request_data = $request->only(
            'product_name',
            'product_desc',
            'product_category',
            'product_price',
            'product_code',
            'locale'
        );

        $product->update($given_request_data);
        $product->save();

        return new JsonResponse($product);
    }

    /**
     * Delete Product
     *
     * @param int $id
     * @return JsonResponse
     */
    public function deleteProductById(int $id): JsonResponse
    {
        $product = Product::findOrFail($id)->first();

        // Not using soft delete
        $product->delete();

        return new JsonResponse('Deleted product.');
    }

    /**
     * Ensure that readable information is sent to the user
     * @param \Exception $e
     */
    private function throwFriendlyErrors(\Exception $e)
    {
        $errors = $e->errors();

        $response = response()->json([
            'message' => 'Invalid data, please re-review the data you sent.',
            'details' => $errors
        ], 422);

        throw new HttpResponseException($response);
    }
}
