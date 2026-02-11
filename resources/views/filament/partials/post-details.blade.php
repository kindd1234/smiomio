<div class="grid grid-cols-3 gap-6 mb-6">
    <div class="bg-white shadow rounded-lg p-5 flex flex-col items-center">
        <span class="text-gray-400 uppercase text-sm">Likes</span>
        <span class="text-2xl font-bold text-gray-800 mt-2">{{ $details['likes']['summary']['total_count'] ?? 0 }}</span>
    </div>
    <div class="bg-white shadow rounded-lg p-5 flex flex-col items-center">
        <span class="text-gray-400 uppercase text-sm">Comments</span>
        <span class="text-2xl font-bold text-gray-800 mt-2">{{ $details['comments']['summary']['total_count'] ?? 0 }}</span>
    </div>
    <div class="bg-white shadow rounded-lg p-5 flex flex-col items-center">
        <span class="text-gray-400 uppercase text-sm">Shares</span>
        <span class="text-2xl font-bold text-gray-800 mt-2">{{ $details['shares']['count'] ?? 0 }}</span>
    </div>
</div>


<!-- Post Images -->
@if(!empty($details['attachments']['data']))
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
        @foreach($details['attachments']['data'] as $attachment)
            @if(isset($attachment['media']['image']['src']))
                <img src="{{ $attachment['media']['image']['src'] }}" alt="Post Image" class="w-full h-auto rounded-lg shadow">
            @endif
        @endforeach
    </div>
@endif

<!-- Latest Comments -->
<div class="bg-white shadow rounded-lg p-4 max-h-96 overflow-y-auto">
    <h3 class="text-lg font-semibold mb-4 text-gray-700">Latest Comments</h3>

    @if (!empty($details['comments']['data']))
        <div class="space-y-4">
            @foreach ($details['comments']['data'] as $comment)
                <div class="border rounded-lg p-3 hover:bg-gray-50 transition duration-150">
                    <div class="font-semibold text-gray-900">{{ $comment['from']['name'] }}</div>
                    <div class="text-gray-700 mt-1 break-words">{{ $comment['message'] }}</div>
                    <div class="text-xs text-gray-500 mt-1">{{ \Carbon\Carbon::parse($comment['created_time'])->diffForHumans() }}</div>
                </div>
            @endforeach
        </div>
    @else
        <div class="text-gray-500 italic">No comments yet.</div>
    @endif
</div>
