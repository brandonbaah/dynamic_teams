@extends('layouts.app')

@section('content')
  
    <div class="accordion" id="accordionWindow">
      @foreach($teams as $team)
        <div class="card">
          <div class="card-header" id="{{ str_replace(' ', '', $team[1]['team_name'] )}}">
            <h2 class="mb-0">
              <button class="btn btn-block text-left" type="button" data-toggle="collapse" data-target="#collapse{{ str_replace(' ', '', $team[1]['team_name'] )}}" aria-expanded="{{ $team == $teams[0] ? true : false}}" aria-controls="collapse{{ str_replace(' ', '', $team[1]['team_name'] )}}">
                {{ $team[1]["team_name"] }} <span class="badge badge-dark badge-pill" style="float: right;">APR: {{$team[1]['ranking_average']}}</span>
              </button>
            </h2>
          </div>

          <div id="collapse{{ str_replace(' ', '', $team[1]['team_name'] )}}" class="{{ $team == $teams[0] ? 'collapse show' : 'collapse'}}" aria-labelledby="{{ str_replace(' ', '', $team[1]['team_name'] )}}" data-parent="#accordionWindow">
            <div class="card-body">
              <ul class="list-group">
              @foreach($team[0] as $player)
                <li class="list-group-item d-flex justify-content-between align-items-center">
                  {{ $player["first_name"] }} {{ $player["last_name"] }}
                  <!-- <span class="badge badge-primary badge-pill">Ranking: {{ $player["ranking"] }}</span> -->
                  @if($player['can_play_goalie'] == 0)
                    <span class="badge badge-primary badge-pill">Ranking: {{ $player["ranking"] }}</span>
                  @else
                    <span class="badge badge-success badge-pill">Goalie - Ranking: {{ $player["ranking"] }}</span>
                  @endif
                </li>
              @endforeach
              </ul>
            </div>
          </div>
        </div>
       @endforeach
    </div>
  
@endsection