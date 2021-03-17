@if(session('status'))
    <div class="w-full text-sm border border-t-8 rounded text-green-700 border-green-600 bg-green-100 px-3 py-4 mb-4 mt-6" role="alert">
        {{ session('status') }}
    </div>
@endif

@if(session('error-message'))
    <div class="w-full text-sm border border-t-8 rounded text-red-700 border-red-600 bg-red-100 px-3 py-4 mb-4 mt-6" role="alert">
        @foreach (session('error-message') as $error)
            <p>{{ $error }}</p>
        @endforeach
    </div>
@endif

@if(session('success-message'))
    <div class="w-full bg-teal-100 border-t-4 border-teal-500 rounded-b text-teal-900 px-4 py-3 shadow-md mb-4 mt-6" role="alert">
        <div class="flex">
            <div class="py-1"><svg class="fill-current h-6 w-6 text-teal-500 mr-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M2.93 17.07A10 10 0 1 1 17.07 2.93 10 10 0 0 1 2.93 17.07zm12.73-1.41A8 8 0 1 0 4.34 4.34a8 8 0 0 0 11.32 11.32zM9 11V9h2v6H9v-4zm0-6h2v2H9V5z"/></svg></div>
            <div>
                <p class="font-bold">Exito!</p>
                @foreach (session('success-message') as $success)
                    <p class="text-sm">{{ $success }}</p>
                @endforeach
            </div>
        </div>
    </div>
@endif

@if (count($errors) > 0)
    <div role="alert" class="w-full mb-4 mt-6">
        <div class="bg-red-500 text-white font-bold rounded-t px-4 py-2">
            Error
        </div>
        <div class="border border-t-0 border-red-400 rounded-b bg-red-100 px-4 py-3 text-red-700">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    </div>
@endif
