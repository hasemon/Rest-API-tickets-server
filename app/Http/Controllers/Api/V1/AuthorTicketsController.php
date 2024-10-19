<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Filters\V1\TicketFilter;
use App\Http\Requests\Api\V1\ReplaceTicketRequest;
use App\Http\Requests\Api\V1\StoreTicketRequest;
use App\Http\Requests\Api\V1\UpdateTicketRequest;
use App\Http\Resources\V1\TicketResource;
use App\Models\Ticket;
use App\Permissions\V1\Abilities;
use App\Policies\V1\TicketPolicy;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class AuthorTicketsController extends ApiController
{
    protected $policyClass = TicketPolicy::class;
    public function index(TicketFilter $filters, $authors_id)
    {
        return TicketResource::collection(Ticket::where('user_id', $authors_id)->filter($filters)->paginate());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTicketRequest $request, $authors_id)
    {
        try {
            $this->isAble('store', Ticket::class);
            return new TicketResource($request->mappedAttributes([
                'author' => 'user_id'
            ]));
        } catch (AuthenticationException $exception) {
            return $this->error('You are not authorized to create a ticket', 401);
        }
    }

    /**
     * Replace the specified resource in storage.
     */
    public function replace(ReplaceTicketRequest $request, $author_id, $ticket_id)
    {
        try {
            $ticket = Ticket::where('id', $ticket_id)
                ->where('user_id', $author_id)
                ->firstOrFail();

            $this->isAble('replace', $ticket);

                $ticket->update($request->mappedAttributes());
                return new TicketResource($ticket);

        } catch (ModelNotFoundException $exception) {
            return $this->error('Ticket not found', 404);
        } catch (AuthenticationException $exception) {
            return $this->error('You are not authorized to replace a ticket', 401);
        }
    }

    /**
     * Replace the specified resource in storage.
     */
    public function update(UpdateTicketRequest $request, $author_id, $ticket_id)
    {
        try {
            $ticket = Ticket::where('id', $ticket_id)
                ->where('user_id', $author_id)
                ->firstOrFail();

            $this->isAble('update', $ticket);

                $ticket->update($request->mappedAttributes());

                return new TicketResource($ticket);

        } catch (ModelNotFoundException $exception) {
            return $this->error('Ticket not found', 404);
        } catch (AuthenticationException $exception) {
            return $this->error('You are not authorized to update a ticket', 401);
        }
    }

    public function destroy($author_id, $ticket_id)
    {
        try {

            $ticket = Ticket::where('id', $ticket_id)
                ->where('user_id', $author_id)
                ->firstOrFail();

            $this->isAble('delete', $ticket);

                $ticket->delete();
                return $this->ok('Ticket successfully deleted');
        } catch (ModelNotFoundException $exception) {
            return $this->error('Ticket not found', 404);
        } catch (AuthenticationException $exception) {
            return $this->error('You are not authorized to delete a ticket', 401);
        }
    }
}
