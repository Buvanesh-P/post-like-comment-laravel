@extends('layout/header');
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Spritle</title>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
</head>

<body>
    <div class="container mt-5">
        <form action="{{route('new.post')}}" method="post">
            @csrf
            <div class="form-group">
                <label for="exampleFormControlTextarea1">Add New Post</label>
                <textarea class="form-control" id="exampleFormControlTextarea1" name="post_desc" rows="3"></textarea>
            </div>
            <button type="submit" class="btn btn-primary">POST</button>
        </form>
    </div>
    <div class="container mt-5">

        @if($postCollection)
        @foreach($postCollection as $post)
        <div class="card mt-2">
            <div class="card-header">
                {{$post->user->first_name}} {{$post->user->last_name}}
            </div>
            <div class="card-body">
                <h5 class="card-title"></h5>
                <p class="card-text" id="post{{$post->id}}">{{$post->post_desc}}</p>
            </div>
            <footer class="blockquote-footer">

                <?php
                $now = time();
                $updated_date = strtotime($post->updated_at->toDateString());
                $datediff = $now - $updated_date;

                $roundDiff = round($datediff / (60 * 60 * 24));
                $updatedAt = ($roundDiff) . ' Days(s) ago';
                if ($roundDiff >= 7 && $roundDiff < 30) {
                    $updatedAt = (round($roundDiff / 7)) . ' Week(s) ago';
                } elseif ($roundDiff >= 30) {
                    $updatedAt = (round($roundDiff / 30)) . ' Month(s) ago';
                }
                ?>
                {{$updatedAt}}
                <div class="actions">
                    @if($actionCollection)
                    <?php
                    $i = 0;
                    ?> @foreach($actionCollection as $action)
                    <?php $i++; ?>
                    @if($action->posts_id == $post->id)
                    @if($action->type == 'like')
                    <button id="like{{$post->id}}" class="btn btn-outline-danger like-post align-right status{{$action->value}}" data-value='{{$action->value}}' data-id='{{$post->id}}' data-userid='{{$post->users_id}}' data-type='like'>Like</button>
                    @break
                    @elseif($i ==count($actionCollection))
                    <button id="like{{$post->id}}" class="btn btn-outline-danger like-post align-right status0" data-value='0' data-id='{{$post->id}}' data-userid='{{$post->users_id}}' data-type='like'>Like</button>
                    @break
                    @endif
                    @elseif($i ==count($actionCollection))
                    <button id="like{{$post->id}}" class="btn btn-outline-danger like-post align-right status0" data-value='0' data-id='{{$post->id}}' data-userid='{{$post->users_id}}' data-type='like'>Like</button>
                    @break
                    @endif

                    @endforeach
                    @endif
                    <button class="btn comment-post btn-outline-secondary align-right" data-id='{{$post->id}}' data-userid='{{$post->users_id}}' data-type='comment' data-firstname='{{$post->user->first_name}}' data-lastname='{{$post->user->last_name}}'>Comment</button>
                </div>
            </footer>
        </div>

        @endforeach
        @endif

    </div>

    <!-- Post popup -->


    <div id="modal-pop" class="modal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id='user_name_modal'>Modal title</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-7" id='post_desc_modal'></div>
                            <div class="col-md-5" id='post_comment_modal'>
                                <div class="form-group">
                                    <label for="exampleFormControlTextarea1">Comments</label>
                                    <textarea class="form-control" name="comment" id="comment-value" placeholder="Comment here..."></textarea>

                                </div>
                                <button class="btn btn-primary" type="button" id="add-new-comment">ADD</button>
                                <input type="hidden" name="post_id" id='post_id_hidden' value="">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- End of post popup -->

</body>

</html>

<script>
    $('body').on('click', '.like-post', function() {
        let postId = $(this).data('id');
        let value = $(this).data('value');
        console.log(value)
        $('#like'+postId).attr('disabled', 'disabled');
        if (value == '1') {
            value = '0';
        } else {
            value = '1';
        }
        console.log(value)
        $.ajax({
            url: 'add-comment',
            type: 'post',
            data: {
                "value": value,
                "posts_id": postId,
                "type": 'like',
                "_token": "{{csrf_token()}}"

            },
            success: function(res) {
                let likeId = '#like' + res.posts_id

                if (res.value == '0') {
                    $(likeId).removeAttr('style');
                } else {
                    $(likeId).css('background', '#dc3545')
                    $(likeId).css('color', 'white')
                }
                $(likeId).data('value', res.value);

                $(likeId).removeAttr('disabled', 'disabled');
            },
            error: function() {
                console.log('failed')
            }

        })

    })

    $('body').on('click', '.comment-post', function() {
        $('.post-comments-popup').remove();
        let postId = $(this).data('id');
        let firstName = $(this).data('firstname');
        let lastName = $(this).data('lastname');
        let postDesc = $('#post' + postId).text();


        $('#post_desc_modal').text(postDesc);
        $('#user_name_modal').text(firstName + '' + lastName);
        $('#post_id_hidden').val(postId);
        $.get('comment?id=' + postId, function(data) {
            console.log(data);
            $.each(data, function(key, val) {
                if ((val.posts_id == postId) && (val.type == 'comment')) {

                    $('<div class="card"><div class="card-body post-comments-popup">' + val.value + '<span> </span></div></div>').insertBefore('#comment-value');
                }
            })
        })
        $('#modal-pop').modal('show');

    })

    $('body').on('click', '#add-new-comment', function() {
        let commentValue = $('#comment-value').val();
        let postId = $('#post_id_hidden').val();
        $('#add-new-comment').attr('disabled', 'disabled');
        $.ajax({
            url: 'add-comment',
            type: 'post',
            data: {
                'value': commentValue,
                'posts_id': postId,
                'type': 'comment',
                "_token": "{{csrf_token()}}"

            },
            success: function(res) {
                $('<div class="post-comments-popup">' + res.value + '<span>' + res.updated_at + '</span></div>').insertBefore('#comment-value');
                $('#comment-value').val('');
                $('#add-new-comment').removeAttr('disabled', 'disabled');
            },
            error: function() {
                console.log(failed)
            }

        })


    })
    $('.status1').css('background', '#dc3545')
    $('.status1').css('color', 'white')
</script>