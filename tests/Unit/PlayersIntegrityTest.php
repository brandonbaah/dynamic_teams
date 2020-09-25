<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\User;
use Faker;


class PlayersIntegrityTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testGoaliePlayersExist () 
    {
/*
		Check there are players that have can_play_goalie set as 1   
*/
    $result = User::where('user_type', 'player')->where('can_play_goalie', 1)->get()->count();
		$this->assertTrue($result > 1);
	
    }
    public function testAtLeastOneGoaliePlayerPerTeam () 
    {
/*
	    calculate how many teams can be made so that there is an even number of teams and they each have between 18-22 players.
	    Then check that there are at least as many players who can play goalie as there are teams
*/  

$goalies = User::where([['user_type', '=', 'player'],['can_play_goalie', '=', 1]])->orderBy('ranking', 'asc')->get();
$players = User::where([['user_type', '=', 'player'],['can_play_goalie', '=', 0]])->orderBy('ranking', 'desc')->get();
$goaliesCount = $goalies->count();
$fieldPlayerCount = $players->count();
$numOfTeams = 0;
$totalAmountOfPlayers = 0;
$index = 0;


while($goaliesCount > 2){

    if($fieldPlayerCount / $goaliesCount >= 17 && $fieldPlayerCount / $goaliesCount <= 21){
        /* The number of teams should be computed in such a way that there should 
        be an even number of teams and each team must have between 18 and 22 players. 
        (Assume the dataset will always fit that criteria */

        $numOfTeams = $goaliesCount;
        break;
    } else {
        $numOfTeams = 0;
    }

    $goaliesCount -= 2;

}

// intdiv - new feature with PHP7 which returns the closest whole number when dividing
$maximumPlayersPerTeam = intdiv($fieldPlayerCount, $numOfTeams);
$totalAmountOfPlayers = $maximumPlayersPerTeam * $numOfTeams;
$emptyTeams = [];


/*
Create nested loop that takes an array of field players in ascending order 
and even distributes them among the n amount of groups. Loop through the array 
(maximumAmountofPlayers) times either removing each item after it is added or creating 
a clone of the existing array that we pull from
*/

$players2 = $players->toArray();

for($x = 0; $x < $numOfTeams; $x++)
{
    $sportsTeams[] = [];

    if(count($sportsTeams) == $numOfTeams)
    {
        $currentIndex = 0;

        for($x = 1; $x < $maximumPlayersPerTeam; $x++)
        {
            if($currentIndex != $totalAmountOfPlayers)
            {
                for($x = 0; $x < count($sportsTeams); $x++)
                {
                    $sportsTeams[$x][] = $players2[$currentIndex];
                    $currentIndex++;
                }
            }
            

        }

    }

}


// Grab Goalies in ascending order
$goalies = User::where([['user_type', '=', 'player'],['can_play_goalie', '=', 1]])->orderBy('ranking', 'asc')->get()->toArray();

// Assign a goalie to each team
while($index < count($goalies)) {
    $sportsTeams[$index][] = $goalies[$index];

    $rankings = array_column($sportsTeams[$index], 'ranking');
    $average = array_sum($rankings) / count($rankings);
    
    // Create Faker Object
    $faker = Faker\Factory::create();

    // Reshape array multidimensionally
    $sportsTeams[$index] = [ $sportsTeams[$index], ["ranking_average" => $average, "team_name" => $faker->city] ];

    $index++;
}
// Cut off the fat
$sportsTeams = array_slice($sportsTeams, 0, $numOfTeams);
$count = 0;


foreach($sportsTeams as $team){
  foreach($team[0] as $player){
    if($player['can_play_goalie']){
      $count++;
    }
  }
}

$this->assertTrue($count == count($sportsTeams));

    }
}
