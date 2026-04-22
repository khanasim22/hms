@role('Patient')
    <div class="d-flex">
        <a href="javascript:void(0)"
        title="{{ __('messages.common.view') }}"
        data-id="{{ $row->id }}"
        class="view-full-complaint btn px-1 text-gray-600 fs-3 ps-0">
            <i class="fa-solid fa-eye"></i>
        </a>
        <a href="javascript:void(0)" title="{{__('messages.common.edit') }}" data-id="{{ $row->id }}"
            class="complaint-edit-btn btn px-1 text-primary fs-3 ps-2">
            <i class="fa-solid fa-pen-to-square"></i>
        </a>
        <a href="javascript:void(0)" title="{{__('messages.common.delete')}}" data-id="{{ $row->id }}" data-title="{{ $row->title }}"
            class="complaint-delete-btn btn px-1 text-danger fs-3 pe-0 {{getCurrentLoginUserLanguageName()=='ar' ? 'me-2' : ''}}" wire:key="{{$row->id}}">
            <i class="fa-solid fa-trash"></i>
        </a>
    </div>
    
@endrole

@role('Admin|Receptionist')
    <a href="javascript:void(0)" title="{{ __('messages.common.view') }}" data-id="{{ $row->id }}" class="view-full-complaint btn px-1 text-gray-600 fs-3 ps-0">
        <i class="fa-solid fa-eye"></i>
    </a>
@endrole