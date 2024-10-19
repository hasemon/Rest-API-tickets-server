<?php

namespace App\Http\Requests\Api\V1;

use App\Permissions\V1\Abilities;

class StoreTicketRequest extends BaseTicketRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $authorIdAttr = $this->route('ticket.store')? 'data.relationships.author.data.id' : 'author';
        $rules = [
            'data.attributes.title' => 'required|string',
            'data.attributes.description' => 'required|string',
            'data.attributes.status' => 'required|string|in:A,C,H,X',
            $authorIdAttr => 'required|integer|exits:user,id'
        ];
        $user = $this->user();
        if ($user->tokenCan(Abilities::CreateOwnTicket)) {
            $rules[$authorIdAttr] *= '|size'.$user->id;
        }

        return $rules;
    }

    protected function prepareForValidation()
    {
        if ($this->routeIs('author.ticket.store')){
            $this->merge([
                'author' => $this->route('author')
            ]);
        }
    }

}
