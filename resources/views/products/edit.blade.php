<x-app-layout>
    <div class="min-h-screen">

        <!-- Main Content -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <h1 class="text-3xl font-bold text-gray-900 mb-8">Edit Product</h1>

            @if (session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('products.update', $product) }}" method="POST" enctype="multipart/form-data" class="bg-white shadow-md rounded-lg p-6">
                @csrf
                @method('PUT')
                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-700">Product Name</label>
                    <input type="text" name="name" id="name" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" value="{{ old('name', $product->name) }}" required>
                </div>
                <div class="mb-4">
                    <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                    <textarea name="description" id="description" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">{{ old('description', $product->description) }}</textarea>
                </div>
                <div class="mb-4">
                    <label for="price" class="block text-sm font-medium text-gray-700">Price ($)</label>
                    <input type="number" name="price" id="price" step="0.01" min="0.01" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" value="{{ old('price', $product->price) }}" required>
                </div>
                <div class="mb-4">
                    <label for="stock" class="block text-sm font-medium text-gray-700">Stock</label>
                    <input type="number" name="stock" id="stock" min="0" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" value="{{ old('stock', $product->stock) }}" required>
                </div>
                <div class="mb-4">
                    <label for="image" class="block text-sm font-medium text-gray-700">Product Image</label>
                    @if ($product->image)
                        <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}" class="w-32 h-32 object-cover mb-2">
                        <label class="inline-flex items-center">
                            <input type="checkbox" name="clear_image" value="1" class="form-checkbox">
                            <span class="ml-2 text-sm text-gray-600">Clear current image</span>
                        </label>
                    @endif
                    <input type="file" name="image" id="image" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                </div>

                <div class="form-group">
                <label for="category">Category</label>
                <select name="category" id="category" class="form-control @error('category') is-invalid @enderror">
                    <option value="">Select a category</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category }}" {{ old('category', $product->category) == $category ? 'selected' : '' }}>{{ $category }}</option>
                    @endforeach
                </select>
                @error('category')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label for="new_category">Or Add New Category</label>
                <input type="text" name="new_category" id="new_category" class="form-control @error('new_category') is-invalid @enderror" value="{{ old('new_category') }}">
                @error('new_category')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

                <div class="flex justify-end space-x-4">
                    <a href="{{ route('products.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded-md hover:bg-gray-700">Cancel</a>
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">Update Product</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>