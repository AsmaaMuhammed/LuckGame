@extends('layouts.app')
@section('scripts')
    <script src="{{ asset('js/admin/game.js') }}"></script>
@endsection
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading col-md-12">

                    <input type="hidden" value="0" id="counter">
                    <input type="hidden" value="0" id="success_attempt_counter">
                    <input type="hidden" value="{{ $user->token }}" id="token" name="user_token">
                    <input type="hidden" value="{{ $user->id }}" id="user_id" name="user_id">
                    <input type="hidden" value="0" name="game_id" id="game_id">
                    <input type="hidden" value="0" id="attempts_nos">
                    <input type="hidden" value="0" id="attempts_time">
                    <table class="table-responsive col-md-12">
                        <tr>
                            <td> <p id="timer"></p></td>
                            <td> Pause the counter at <p id="b"></p> for 3 times.</td>
                        </tr>
                        <tr>
                            <td> <button id="start" route="{{route('startGame')}}">New Game</button> </td>
                            <td> <button id="new_attempt" route="{{route('endGame')}}" style="display: none;">Click</button> </td>
                            <td> <button id="states">States</button> </td>
                        </tr>
                     </table>

                </div>

                <div class="panel-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                </div>
                <div class="panel-body" id="states_data" style="display: none;">
                    <table class="table-responsive col-md-12">
                        <tr>
                            <td> All Games : {{ $user_states['totals']['total'] }}</td>
                            <td> Ended Games : {{ $user_states['totals']['ended'] }}</td>
                        </tr>
                        <tr>
                            <td> Average Attempts Time : {{ $user_states['games_time']['average'] }}</td>
                            <td> Best Attempts Time : {{ $user_states['games_time']['best'] }}</td>
                            <td> Worst Attempts Time : {{ $user_states['games_time']['worst'] }}</td>
                        </tr>
                        <tr>
                            <td> Average Attempts Count : {{ $user_states['games_attempts']['average'] }}</td>
                            <td> Best Attempts Count : {{ $user_states['games_attempts']['best'] }}</td>
                            <td> Worst Attempts Count : {{ $user_states['games_attempts']['worst'] }}</td>
                        </tr>
                        <tr>
                            <td> Rank : {{ $user_states['rank']['me'] }}</td>
                            <td> Total : {{ $user_states['rank']['total'] }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
