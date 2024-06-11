<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>News api</title>
</head>

<body>
    <style>
        .row {
            display: grid;
            grid-template-columns: repeat(3, 1fr)
        }
    </style>
    <div class="row">
        <div class="clo-6">
            @forelse ($users as $user)
                <div>
                    <img src="{{ asset($user->image ?? 'images/favicon.jfif') }}" alt="" srcset=""
                        style="width: 200px; height:200px;">
                </div>
            @empty
                <h1>No users Yet</h1>
            @endforelse
        </div>
        <div class="col-6">
            @forelse ($news as $article)
                <div>
                    <img src="{{ asset($article->image ?? 'images/favicon.jfif') }}" alt="" srcset=""
                        style="width: 200px; height:200px;">
                </div>
            @empty
                <h1>No News Yet</h1>
            @endforelse
        </div>
        <div class="col-6">
            @forelse ($images as $image)
                <div>
                    <img src="{{ asset($image->path) }}" alt="" srcset=""
                        style="width: 200px; height:200px;">
                </div>
            @empty
                <h1>No News Yet</h1>
            @endforelse
        </div>
    </div>
</body>

</html>
