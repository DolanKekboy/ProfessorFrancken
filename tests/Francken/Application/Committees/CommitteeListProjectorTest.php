<?php

declare(strict_types=1);

namespace Francken\Tests\Application\Committees;

use Francken\Application\Committees\CommitteesList;
use Francken\Application\Committees\CommitteesListProjector as CommitteeListProjector;
use Francken\Application\Committees\CommitteesListRepository;
use Francken\Application\Projector;
use Francken\Application\ReadModel\MemberList\MemberList;
use Francken\Application\ReadModel\MemberList\MemberListRepository;
use Francken\Domain\Committees\CommitteeId;
use Francken\Domain\Committees\Events\CommitteeEmailChanged;
use Francken\Domain\Committees\Events\CommitteeGoalChanged;
use Francken\Domain\Committees\Events\CommitteeInstantiated;
use Francken\Domain\Committees\Events\CommitteeNameChanged;
use Francken\Domain\Committees\Events\CommitteePageChanged;
use Francken\Domain\Committees\Events\MemberJoinedCommittee;
use Francken\Domain\Committees\Events\MemberLeftCommittee;
use Francken\Domain\Members\Email;
use Francken\Domain\Members\MemberId;
use Francken\Infrastructure\Repositories\InMemoryRepository;
use Francken\Tests\Application\ProjectorScenarioTestCase as TestCase;
use League\CommonMark\CommonMarkConverter;

class CommitteeListProjectorTest extends TestCase
{
    private $members;

    /** @test */
    function it_stores_a_committee()
    {
        $id = CommitteeId::generate();

        $this->scenario->when(
            new CommitteeInstantiated($id, 'S[ck]rip(t|t?c)ie', 'Digital anarchy')
        )->then([
            new CommitteesList($id, 'S[ck]rip(t|t?c)ie', 'Digital anarchy')
        ]);
    }

    /** @test */
    function it_changes_the_properties_of_a_committee()
    {
        $id = CommitteeId::generate();

        $this->scenario->given([
            new CommitteeInstantiated($id, 'S[ck]rip(t|t?c)ie', 'Digital anarchy')
        ])->when(
            new CommitteeNameChanged($id, 'Compucie')
        )->then([
            new CommitteesList($id, 'Compucie', 'Digital anarchy')
        ])->when(
            new CommitteeGoalChanged($id, 'Markt verovering')
        )->then([
            new CommitteesList($id, 'Compucie', 'Markt verovering')
        ])->when(
            new CommitteeEmailChanged($id, new Email('scriptcie@professorfrancken.nl'))
        )->then([
            new CommitteesList($id, 'Compucie', 'Markt verovering', new Email('scriptcie@professorfrancken.nl'))
        ])->when(
            new CommitteeEmailChanged($id, null)
        )->then([
            new CommitteesList($id, 'Compucie', 'Markt verovering', null)
        ]);
    }

    /** @test */
    function it_can_store_a_committee_page_using_mark_down()
    {
        $id = CommitteeId::generate();

        $this->scenario->given([
            new CommitteeInstantiated($id, 'S[ck]rip(t|t?c)ie', 'Digital anarchy')
        ])->when(
            new CommitteePageChanged($id, '# Title')
        )->then([
            new CommitteesList($id, 'S[ck]rip(t|t?c)ie', 'Digital anarchy', null, '# Title', "<h1>Title</h1>\n")
        ]);
    }

    /** @test */
    function it_stores_information_about_members()
    {
        $this->members->save(
            new MemberList(
                $memberId = new MemberId('6bd3f9b9-b910-4d4c-89ea-2f9af285c9bf'),
                'Mark',
                'Redeman'
            )
        );

        $id = CommitteeId::generate();
        $this->scenario->given([
            new CommitteeInstantiated($id, 'S[ck]rip(t|t?c)ie', 'Digital anarchy')
        ])->when(
            new MemberJoinedCommittee($id, $memberId)
        )->then([
            new CommitteesList($id,
                'S[ck]rip(t|t?c)ie',
                'Digital anarchy',
                null,
                '',
                '',
                [[
                    'id' => '6bd3f9b9-b910-4d4c-89ea-2f9af285c9bf',
                    'first_name' => 'Mark',
                    'last_name' => 'Redeman'
                ]])
        ])->when(
            new MemberLeftCommittee($id, $memberId)
        )->then([
            new CommitteesList(
                $id,
                'S[ck]rip(t|t?c)ie',
                'Digital anarchy',
                null,
                '',
                '',
                [])
        ]);
    }

    protected function createProjector(InMemoryRepository $repository) : Projector
    {
        $this->members = new InMemoryRepository();

        return new CommitteeListProjector(
            new CommitteesListRepository(
                $repository
            ),
            new MemberListRepository(
                $this->members
            ),
            new CommonMarkConverter());
    }
}
