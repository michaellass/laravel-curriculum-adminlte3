<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use App\Organization;
use App\Http\Requests\MassDestroyOrganizationRequest;
use App\StatusDefinition;
use App\OrganizationRoleUser;
use Yajra\DataTables\DataTables;


class OrganizationsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        abort_unless(\Gate::allows('organization_access'), 403);
        $organizations = auth()->user()->organizations(); // Todo: Admins should see all Organizations
        
        return view('organizations.index')
                ->with(compact('organizations')); 
    }

    public function list()
    {
        abort_unless(\Gate::allows('organization_access'), 403);
        $organizations = (auth()->user()->role()->id == 1) ? Organization::all() : auth()->user()->organizations()->get();
              
        return DataTables::of($organizations)
            ->addColumn('status', function ($organizations) {
                return $organizations->status()->first()->lang_de;                
            })
            ->addColumn('action', function ($organizations) {
                 $actions  = '';
                    if (\Gate::allows('organization_edit')){
                        $actions .= '<a href="'.route('organizations.edit', $organizations->id).'"'
                                    . 'id="edit-organization-'.$organizations->id.'" '
                                    . 'class="btn p-1">'
                                    . '<i class="fa fa-pencil-alt"></i>' 
                                    . '</a>';
                    }
                    if (\Gate::allows('organization_delete')){
                        $actions .= '<button type="button" '
                                . 'class="btn text-danger" '
                                . 'onclick="destroyDataTableEntry(\'organizations\','.$organizations->id.')">'
                                . '<i class="fa fa-trash"></i></button>';
                    }
              
                return $actions;
            })
           
            ->addColumn('check', '')
            ->setRowId('id')
            ->setRowAttr([
                'color' => 'primary',
            ])
            ->make(true);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        abort_unless(\Gate::allows('organization_create'), 403);
        $status_definitions = StatusDefinition::all();
        
        return view('organizations.create')
                ->with(compact('status_definitions'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        abort_unless(\Gate::allows('organization_create'), 403);
        $organization = Organization::firstOrCreate($this->validateRequest());
        
        // axios call? 
        if (request()->wantsJson()){    
            return ['message' => $organization->path()];
        }
        //dd($organization->path());
        return redirect($organization->path());
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Organization $organization)
    {
        abort_unless(\Gate::allows('organization_show'), 403);
        // axios call? 
        if (request()->wantsJson()){   
            return [
                'message' => $organization
            ];
        }
        $status_definitions = StatusDefinition::all();
        
        return view('organizations.show')
                ->with(compact('organization'))
                ->with(compact('status_definitions'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Organization $organization)
    {
        abort_unless(\Gate::allows('organization_edit'), 403);
        $status_definitions = StatusDefinition::all();
        return view('organizations.edit')
                ->with(compact('organization'))
                ->with(compact('status_definitions'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int                      $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Organization $organization)
    { 
        abort_unless(\Gate::allows('organization_edit'), 403);
        $clean_data = $this->validateRequest();        
        if (isset(request()->status_id[0]))
        {
            $clean_data['status_id'] =  request()->status_id[0];  //hack to prevent array to string conversion
        }
        
        $organization->update($clean_data);
        
        return redirect($organization->path());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Organization $organization)
    {
        abort_unless(\Gate::allows('organization_delete'), 403);

        $organization->delete();

        return back();
    }
    
    public function massDestroy(MassDestroyOrganizationRequest $request)
    {
        abort_unless(\Gate::allows('organization_delete'), 403);
        Organization::whereIn('id', request('ids'))->delete();

        return response(null, 204);
    }
    
    public function enrol()
    {
        abort_unless(\Gate::allows('organization_enrolment'), 403);
        
        foreach ((request()->enrollment_list) AS $enrolment)
        {  
            
            $return[] = OrganizationRoleUser::updateOrCreate(
                    [
                        'user_id'         => $enrolment['user_id'],
                        'organization_id' => $enrolment['organization_id']
                    ],
                    [
                        'role_id'         => $enrolment['role_id']
                    ]
                );
        }
        
        return $return;  
    }
    
    public function expel()
    {
        abort_unless(\Gate::allows('organization_enrolment'), 403);
        
        foreach ((request()->expel_list) AS $expel)
        {  
            $return[] = OrganizationRoleUser::where([
                                        'user_id'         => $expel['user_id'],
                                        'organization_id' => $expel['organization_id'],
                                    ])->delete();
        }
        
        return $return;  
    }
    
    protected function validateRequest()
    {               
        
        return request()->validate([
            'title'         => 'sometimes|required',
            'description'   => 'sometimes',
            'street'        => 'sometimes',
            'postcode'      => 'sometimes',
            'city'          => 'sometimes',
            'state_id'      => 'sometimes',
            'country_id'    => 'sometimes',
            'organization_type_id' => 'sometimes',
            'phone'         => 'sometimes',
            'email'         => 'sometimes',
            'status_id'     => 'sometimes',
            ]);
    }
}
