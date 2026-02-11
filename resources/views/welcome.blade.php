<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Form Page</title>

  <!-- Tailwind CSS CDN -->
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">

  <div class="w-full max-w-md bg-white shadow-lg rounded-lg p-6">
    <h1 class="text-xl font-semibold text-gray-800 mb-4 text-center">Submit Information</h1>

    <form class="space-y-4">
      <!-- Page ID -->
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Page ID</label>
        <input 
          type="text" 
          class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none"
          placeholder="Enter Page ID"
        />
      </div>

      <!-- Access Token -->
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Access Token</label>
        <input 
          type="text" 
          class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none"
          placeholder="Enter Access Token"
        />
      </div>

      <!-- Title -->
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Title</label>
        <input 
          type="text" 
          class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none"
          placeholder="Enter Title"
        />
      </div>

      <!-- Image -->
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Image</label>
        <input 
          type="file" 
          class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none file:mr-3 file:px-3 file:py-1 file:border-0 file:bg-blue-600 file:text-white file:rounded-md"
        />
      </div>

      <!-- Submit Button -->
      <button 
        type="submit"
        class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition"
      >
        Submit
      </button>
    </form>
  </div>

</body>
</html>
