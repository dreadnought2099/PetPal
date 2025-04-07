@extends('layouts.app')

@section('content')
    @php
        // Fetch and decode the JSON content from the file
        $jsonContent = file_get_contents(public_path('data/about.json'));
        $aboutData = json_decode($jsonContent, true);
    @endphp

    <div class="min-h-[70vh] flex flex-col items-center text-center justify-center space-y-12 px-6 md:px-16">
        @foreach (['about' => 'What is <span class="text-primary">PetPal?</span> ', 'problem' => '<span class="text-primary">Problems</span> Encountered'] as $key => $title)
            <div class="max-w-3xl mx-auto md:px-8">
                <h1 class="text-4xl md:text-5xl font-bold text-dark leading-snug">
                    {!! $title !!}
                </h1>
                <div class="mt-8 space-y-6 text-lg text-gray-700 text-left md:text-center">
                    @foreach ($aboutData[$key]['content'] as $content)
                        <p class="leading-relaxed md:leading-loose tracking-wide">
                            {{ $content }}
                        </p>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
@endsection
