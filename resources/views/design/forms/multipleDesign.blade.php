@extends('layouts.app')

@php
    $equipment = \App\Equipment::whereIn('type', [\App\Statics\Statics::EQUIPMENT_TYPE_INVERTER, \App\Statics\Statics::EQUIPMENT_TYPE_MODULE, \App\Statics\Statics::EQUIPMENT_TYPE_MONITOR, \App\Statics\Statics::EQUIPMENT_TYPE_RACKING ])->get(['name', 'model', 'type']);
    $monitorSelect = [];
    $inverterSelect = [];
    $moduleSelect = [];
    $rackingSelect = [];
    foreach ($equipment as $item){
        if ($item->type === \App\Statics\Statics::EQUIPMENT_TYPE_INVERTER)
            $inverterSelect[$item->name] = null;
        elseif ($item->type === \App\Statics\Statics::EQUIPMENT_TYPE_MODULE)
            $moduleSelect[$item->name] = null;
        elseif ($item->type === \App\Statics\Statics::EQUIPMENT_TYPE_RACKING)
            $rackingSelect[$item->name] = null;
        elseif ($item->type === \App\Statics\Statics::EQUIPMENT_TYPE_MONITOR)
            $monitorSelect[$item->name] = null;
    }
@endphp

@section('content')
<style>
    .uppy-Dashboard-inner{
        margin-right: auto!important;
        margin-left: auto!important;
        height: 450px !important;
        width: 700px !important;
    }    
</style>
<div class="container-fluid">
    <div class="row">
        <div class="col s12">
            <h3 class="prussian-blue-text capitalize">Multiple</h3>
            <h5>Design Request</h5>
        </div>
        <div class="col s12">
            <div class="card" style="margin-top:-2%;">
                <div class="wizard-content" style="padding-bottom:2%;">
                    <form id="array_form" enctype="multipart/form-data" class="validation-wizard wizard-circle m-t-40">
                    @csrf
                    <input type="hidden" name="project_id" value="{{$project_id}}">
                  
                   
                    @if(in_array('aurora design',$type))
                    <h6>Aurora Design</h6>
                    <section>
                        <div class="row">
                            <div class="col s12">
                                <h6 class="red-text capitalize">* Mandatory Fields</h6>
                            </div>
                        </div>
                        <div class="row valign-wrapper">
                            <div class="input-field col s6">
                            <input type="hidden" name="aurora_design" id="aurora_design" value="aurora design">
                                <div class="switch center">
                                    <label>
                                        Max System Size
                                        <input type="checkbox" id="hoa" onclick="toggleSystemSize(this)">
                                        <span class="lever"></span>
                                        Limited System Size
                                    </label>
                                </div>
                            </div>
                            <div class="input-field col s6">
                                <input id="system_size" name="system_size" type="text" disabled value="maximum">
                                <label for="system_size">System Size</label>
                                <span class="helper-text">Required</span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col s6">
                                <input id="annual_usage" name="annual_usage" type="number" validate="annual_usage" class="required">
                                <label for="annual_usage">Annual Usage <span class="red-text lead">*</span></label>
                                <span class="helper-text" data-error="Enter a value greater than 1">Required</span>
                            </div>
                            <div class="input-field col s6">
                                <input id="max_offset" name="max_offset" type="number" validate="offset" class="required">
                                <label for="max_offset">Max Offset % <span class="red-text lead">*</span></label>
                                <span class="helper-text" data-error="Enter a value between 1 and 200">Required. Max 200%</span>
                            </div>
                        </div>
                        <div class="row valign-wrapper">
                            <div class="input-field col s4">
                                <div class="switch center">
                                <label class="tooltipped" data-position="top" data-delay="10" data-tooltip="House Owner Association"> 
                                        HOA? &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;No
                                        <input type="checkbox" onclick="toggleHOA(this)">
                                        <span class="lever"></span>
                                        Yes
                                    </label>
                                </div>
                            </div>
                            <div class="input-field col s4">
                                <select name="installation" id="installation" disabled>
                                    <option value="none" selected>None</option>
                                    <option value="front roof">Front Roof</option>
                                    <option value="black roof">Back Roof</option>
                                    <option value="garage">Garage</option>
                                </select>
                                <label for="installation">Installation restrictions</label>
                                <span class="helper-text" data-error="This field is required" data-success="">Required</span>
                            </div>
                            <div class="input-field col s4">
                                <input id="remarks" name="remarks" type="text" disabled value="None">
                                <label for="remarks">Remarks</label>
                            </div>
                        </div>
                        <div class="row">
                                            @if($project_type == 'commercial')
                                                <div class="row">
                                                    <div class="col s4">
                                                        @component('components.autocomplete', ["name" => "module", "data" => $moduleSelect])@endcomponent
                                                    </div>
                                                    <div class="col s4">
                                                        <div class="input-field col s12">
                                                            <input id="moduleType" name="moduleType" type="text"  value="" placeholder="Mention (watts)">
                                                            <label for="moduleType">Module Type <span class="red-text lead">*</span></label>                                            
                                                        </div>
                                                    </div>
                                                    <div class="col s4">
                                                        <div class="input-field col s12" id="moduleOther_input" style="display:none;">
                                                            <input type="text" name="moduleOther" id="moduleOther" value="moduleOther">
                                                            <label for="moduleOther">Other:  <span class="red-text lead">*</span></label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col s4">
                                                        @component('components.autocomplete', ["name" => "inverter", "data" => $inverterSelect])@endcomponent
                                                    </div>
                                                    <div class="col s4">
                                                        <div class="input-field col s12">
                                                            <input id="inverterType" name="inverterType" type="text"  value="" placeholder="Mention">
                                                            <label for="inverterType">Inverter Type <span class="red-text lead">*</span></label>
                                                        </div>
                                                    </div>
                                                    <div class="col s4">
                                                        <div class="input-field col s12" id="inverterOther_input" style="display:none;">
                                                            <input type="text" name="inverterOther" id="inverterOther" value="inverterOther">
                                                            <label for="inverterOther">Other:  <span class="red-text lead">*</span></label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col s4">
                                                        @component('components.autocomplete', ["name" => "racking", "data" => $rackingSelect])@endcomponent
                                                    </div>
                                                    <div class="col s4">
                                                        <div class="input-field col s12">
                                                            <input id="rackingType" name="rackingType" type="text"  value="" placeholder="Mention">
                                                            <label for="rackingType">Racking Type <span class="red-text lead">*</span></label>
                                                        </div>
                                                    </div>
                                                    <div class="col s4">
                                                        <div class="input-field col s12" id="rackingOther_input" style="display:none;">
                                                            <input type="text" name="rackingOther" id="rackingOther" value="rackingOther">
                                                            <label for="rackingOther">Other:  <span class="red-text lead">*</span></label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col s4">
                                                        @component('components.autocomplete', ["name" => "monitor", "data" => $monitorSelect])@endcomponent
                                                    </div>
                                                    <div class="col s4">
                                                        <div class="input-field col s12">
                                                            <input id="monitorType" name="monitorType" type="text"  value="" placeholder="Mention">
                                                            <label for="monitorType">Monitor Type <span class="red-text lead">*</span></label>
                                                        </div>
                                                    </div>
                                                    <div class="col s4">
                                                        <div class="input-field col s12" id="monitorOther_input" style="display:none;" >
                                                            <input type="text" name="monitorOther" id="monitorOther" value="monitorOther">
                                                            <label for="monitorOther">Other:  <span class="red-text lead">*</span></label>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                            @if($project_type == 'residential')
                                                <div class="row">
                                                    <div class="col s4">
                                                        @component('components.autocomplete', ["name" => "module", "data" => $moduleSelect])@endcomponent
                                                    </div>
                                                    <div class="col s4">
                                                        <div class="input-field col s12">
                                                            <input id="moduleType" name="moduleType" type="text"  value="" placeholder="Mention (watts)">
                                                            <label for="moduleType">Module Type <span class="red-text lead">*</span></label>                                            
                                                        </div>
                                                    </div>
                                                    <div class="col s4">
                                                        <div class="input-field col s12" id="moduleOther_input" style="display:none;">
                                                            <input type="text" name="moduleOther" id="moduleOther" value="moduleOther">
                                                            <label for="moduleOther">Other:  <span class="red-text lead">*</span></label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col s4">
                                                        @component('components.autocomplete', ["name" => "inverter", "data" => $inverterSelect])@endcomponent
                                                    </div>
                                                    <div class="col s4">
                                                        <div class="input-field col s12">
                                                            <input id="inverterType" name="inverterType" type="text"  value="" placeholder="Mention">
                                                            <label for="inverterType">Inverter Type <span class="red-text lead">*</span></label>
                                                        </div>
                                                    </div>
                                                    <div class="col s4">
                                                        <div class="input-field col s12" id="inverterOther_input" style="display:none;">
                                                            <input type="text" name="inverterOther" id="inverterOther" value="inverterOther">
                                                            <label for="inverterOther">Other:  <span class="red-text lead">*</span></label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col s4">
                                                        @component('components.autocomplete', ["name" => "racking", "data" => $rackingSelect])@endcomponent
                                                    </div>
                                                    <div class="col s4">
                                                        <div class="input-field col s12">
                                                            <input id="rackingType" name="rackingType" type="text"  value="" placeholder="Mention">
                                                            <label for="rackingType">Racking Type <span class="red-text lead">*</span></label>
                                                        </div>
                                                    </div>
                                                    <div class="col s4">
                                                        <div class="input-field col s12" id="rackingOther_input" style="display:none;">
                                                            <input type="text" name="rackingOther" id="rackingOther" value="rackingOther">
                                                            <label for="rackingOther">Other:  <span class="red-text lead">*</span></label>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                        </div>
                        <div class="row">
                            <div class="col s12">
                                <blockquote style="padding-left: 0.5em">Anything else we should know? Drop it here:</blockquote>
                                <div class="input-field">
                                    <textarea id="notes" name="notes" class="materialize-textarea"></textarea>
                                    <label for="notes">Notes</label>
                                </div>
                            </div>
                        </div>
                        <div class="row" style="margin-bottom:2%;">
                            <div class="col s12">
                                <h4 class="mt-2">Supporting Documents</h4>
                                <div class="mh-a" id="uppyAurora"></div>
                                <div class="center">
                                    <span class="helper-text imperial-red-text" id="files_error"></span>
                                </div>
                            </div>
                        </div>
                        
                    </section>
                    @endif
@if(in_array('structural load letter and calculations',$type))
                    <h6>Structural Load Letter and Calculation</h6>
                    <section>
                        <div class="row">
                            <div class="col s12">
                                <h6 class="red-text capitalize">* Mandatory Fields</h6>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col s12 input-field">
                            <input type="hidden" name="structural_load_letter_and_calculations" id="structural_load_letter_and_calculations" value="structural load letter and calculations">
                                <select id="roof_type">
                                    <option value="Asphalt">Asphalt</option>
                                    <option value="Cedar Shake">Cedar Shake</option>
                                    <option value="Clay">Clay</option>
                                    <option value="Flat Rolled">Flat Rolled</option>
                                    <option value="Metal - Shingle">Metal - Shingle</option>
                                    <option value="Metal - Standing Seam">Metal - Standing Seam</option>
                                    <option value="Shingles">Shingles</option>
                                </select>
                                <label for="roof_type">Roof Type</label>
                            </div>
                            <div class="col s12">
                                <h5>Arrays</h5>
                                <div class="row col s12">
                                @if($project_type == 'commercial')
                                    <div class="row">
                                        <div class="col s4">
                                            @component('components.autocomplete1', ["name" => "module", "data" => $moduleSelect])@endcomponent
                                        </div>
                                        <div class="col s4">
                                            <div class="input-field col s12">
                                                <input id="moduleType" name="moduleType" type="text"  value="" placeholder="Mention (watts)">
                                                <label for="moduleType">Module Type <span class="red-text lead">*</span></label>                                            
                                            </div>
                                            <div class="col s4">
                                                <div class="input-field col s12" id="moduleOther_input1" style="display:none;">
                                                    <input type="text" name="moduleOther" id="moduleOther1" value="moduleOther">
                                                    <label for="moduleOther">Other:  <span class="red-text lead">*</span></label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                        <div class="row">
                                            <div class="col s4">
                                                @component('components.autocomplete1', ["name" => "inverter", "data" => $inverterSelect])@endcomponent
                                            </div>
                                            <div class="col s4">
                                                <div class="input-field col s12">
                                                    <input id="inverterType" name="inverterType" type="text"  value="" placeholder="Mention">
                                                    <label for="inverterType">Inverter Type <span class="red-text lead">*</span></label>
                                                </div>
                                            </div>
                                            <div class="col s4">
                                                <div class="input-field col s12" id="inverterOther_input1" style="display:none;">
                                                    <input type="text" name="inverterOther" id="inverterOther1" value="inverterOther">
                                                    <label for="inverterOther">Other:  <span class="red-text lead">*</span></label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col s4">
                                                @component('components.autocomplete1', ["name" => "racking", "data" => $rackingSelect])@endcomponent
                                            </div>
                                            <div class="col s4">
                                                <div class="input-field col s12">
                                                    <input id="rackingType" name="rackingType" type="text"  value="" placeholder="Mention">
                                                    <label for="rackingType">Racking Type <span class="red-text lead">*</span></label>
                                                </div>
                                            </div>
                                            <div class="col s4">
                                                <div class="input-field col s12" id="rackingOther_input1" style="display:none;">
                                                    <input type="text" name="rackingOther" id="rackingOther1" value="rackingOther">
                                                    <label for="rackingOther">Other:  <span class="red-text lead">*</span></label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col s4">
                                                @component('components.autocomplete1', ["name" => "monitor", "data" => $monitorSelect])@endcomponent
                                            </div>
                                            <div class="col s4">
                                                <div class="input-field col s12">
                                                    <input id="monitorType" name="monitorType" type="text"  value="" placeholder="Mention">
                                                    <label for="monitorType">Monitor Type <span class="red-text lead">*</span></label>
                                                </div>
                                            </div>
                                            <div class="col s4">
                                                <div class="input-field col s12" id="monitorOther_input1" style="display:none;" >
                                                    <input type="text" name="monitorOther" id="monitorOther1" value="monitorOther">
                                                    <label for="monitorOther">Other:  <span class="red-text lead">*</span></label>
                                                </div>
                                            </div>
                                        </div>
                                        @endif
                                        @if($project_type == 'residential')
                                        <div class="row">
                                            <div class="col s4">
                                                @component('components.autocomplete1', ["name" => "module", "data" => $moduleSelect])@endcomponent
                                            </div>
                                            <div class="col s4">
                                                <div class="input-field col s12">
                                                    <input id="moduleType" name="moduleType" type="text"  value="" placeholder="Mention (watts)">
                                                    <label for="moduleType">Module Type <span class="red-text lead">*</span></label>                                            
                                                </div>
                                            </div>
                                            <div class="col s4">
                                                <div class="input-field col s12" id="moduleOther_input1" style="display:none;">
                                                    <input type="text" name="moduleOther" id="moduleOther1" value="moduleOther">
                                                    <label for="moduleOther">Other:  <span class="red-text lead">*</span></label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col s4">
                                                @component('components.autocomplete1', ["name" => "inverter", "data" => $inverterSelect])@endcomponent
                                            </div>
                                            <div class="col s4">
                                                <div class="input-field col s12">
                                                    <input id="inverterType" name="inverterType" type="text"  value="" placeholder="Mention">
                                                    <label for="inverterType">Inverter Type <span class="red-text lead">*</span></label>
                                                </div>
                                            </div>
                                            <div class="col s4">
                                                <div class="input-field col s12" id="inverterOther_input1" style="display:none;">
                                                    <input type="text" name="inverterOther" id="inverterOther1" value="inverterOther">
                                                    <label for="inverterOther">Other:  <span class="red-text lead">*</span></label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col s4">
                                                @component('components.autocomplete1', ["name" => "racking", "data" => $rackingSelect])@endcomponent
                                            </div>
                                            <div class="col s4">
                                                <div class="input-field col s12">
                                                    <input id="rackingType" name="rackingType" type="text"  value="" placeholder="Mention">
                                                    <label for="rackingType">Racking Type <span class="red-text lead">*</span></label>
                                                </div>
                                            </div>
                                            <div class="col s4">
                                                <div class="input-field col s12" id="rackingOther_input1" style="display:none;">
                                                    <input type="text" name="rackingOther" id="rackingOther1" value="rackingOther">
                                                    <label for="rackingOther">Other:  <span class="red-text lead">*</span></label>
                                                </div>
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                <div class="row">
                                    <div class="col s12 m4 input-field">
                                        <input id="panels" name="panels" validate="panels" type="number" value="1" class="required">
                                        <label for="panels"># of Panels <span class="red-text lead">*</span></label>
                                        <span class="helper-text">Required. at least 1</span>
                                    </div>
                                    <div class="col s12 m4 input-field">
                                        <input id="tilt" name="tilt" validate="tilt" type="number" value="0" class="required">
                                        <label for="tilt">Tilt <span class="red-text lead">*</span></label>
                                        <span class="helper-text" data-error="Enter a value greater than equal to 0 and less than equal to 90">Required 0-90</span>
                                    </div>
                                    <div class="col s12 m4 input-field">
                                        <input id="azimuth" name="azimuth" validate="azimuth" type="number" value="0" class="required">
                                        <label for="azimuth">Azimuth <span class="red-text lead">*</span></label>
                                        <span class="helper-text" data-error="Enter a value greater than equal to 0 and less than equal to 360">Required 0-360</span>
                                    </div>
                                    <div class="col s12 center center-align">
                                        <button type="button" class="btn imperial-red-outline-button" id="add_array">Add Array</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col s12">
                                <table class="striped table responsive-table white black-text">
                                    <thead>
                                    <tr>
                                        <th class="center">Module</th>
                                        <th class="center">Inverter</th>
                                    @if($project_type == 'commercial')
                                        <th class="center">Racking</th>
                                        <th class="center">Monitor</th>
                                    @else
                                        <th class="center">Racking</th>
                                    @endif
                                        <th class="center"># Panels</th>
                                        <th class="center">Tilt</th>
                                        <th class="center">Azimuth</th>
                                        <th class="center"></th>
                                    </tr>
                                    </thead>
                                    <tbody id="array_table">
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="row" style="margin-bottom:2%;">
                            <div class="col s12"><br>
                                <h4 class="mt-2">Supporting Documents</h4>
                                <div class="mh-a" id="uppyStructural"></div>
                                <div class="center">
                                    <span class="helper-text imperial-red-text" id="files_error"></span>
                                </div>
                            </div>
                        </div>
                        
                    </section>
@endif
@if(in_array('PE stamping',$type))
                    <h6>PE Stamping</h6>
                    <section>
                        <div class="row">
                            <div class="col s12">
                                <h6 class="red-text capitalize">* Mandatory Fields</h6>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col s6">
                                <div class="input-field">
                                <input type="hidden" name="pe_stamping" id="pe_stamping" value="pe stamping">
                                    <div class="col s12">
                                    <h4>Supporting Documents(Site Survey Pictures)</h4>
                                    <div class="mh-a" id="uppystructural_letter"></div>
                                        <div class="">
                                            <span class="helper-text imperial-red-text" id="files_error"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col s6">
                                <div class="input-field">
                                    <div class="col s12">
                                    <h4>Engineering Plan Set</h4>
                                    <div class="mh-a" id="uppyplanset"></div>
                                        <div class="">
                                            <span class="helper-text imperial-red-text" id="files_error"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col s6">
                                <div class="input-field">
                                    <p>
                                        <label>
                                            <input type="checkbox" name="structural_letter" id="structural_letter"/>
                                            <span>Structural Letter</span>
                                        </label>
                                    </p>
                                </div>
                            </div>
                            <div class="col s6">
                                <div class="input-field">
                                    <p>
                                        <label>
                                            <input type="checkbox" name="electrical_stamps" id="electrical_stamps"/>
                                            <span>Electrical Stamps</span>
                                        </label>
                                    </p>
                                </div>
                            </div>
                            <div class="col s4">
                                <!-- <div class="input-field">
                                    <input placeholder=" " type="text" class="validate" readonly>
                                    <label for="total_price">Price: </label>
                                </div> -->
                            </div>
                        </div>
                        
                    </section>
                    @endif
                @if(in_array('electrical load calculations',$type))
                    <h6>Electrical Load Calculations</h6>
                    <section>
                        <div class="row">
                            <div class="col s12">
                                <h6 class="red-text capitalize">* Mandatory Fields</h6>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col s6">
                                <div class="input-field col s12">
                                <input type="hidden" name="electrical_load_calculations" id="electrical_load_calculations" value="electrical load calculations">
                                    <select id="selectItem1" multiple onchange="getSelectedValue('1')">
                                        <option value="" disabled>Choose your option</option>
                                        <option value="Refrigerator_w_freezer">Refrigerator w/freezer</option>
                                        <option value="Freezer-Chest">Freezer - Chest</option>
                                        <option value="Freezer-Upright">Freezer - Upright</option>
                                        <option value="Dishwasher">Dishwasher</option>
                                        <option value="Range">Range</option>
                                        <option value="Oven">Oven</option>
                                        <option value="Microwave">Microwave</option>
                                        <option value="Toaster_oven">Toaster oven</option>
                                        <option value="Coffee_maker">Coffee maker</option>
                                        <option value="Garbage_disposal">Garbage disposal</option>
                                        <option value="Well_pump_1/2_HP">Well pump 1/2 HP</option>
                                    </select>
                                    <label>Kitchen</label>
                                </div>
                            </div>
                            <div class="col s6">
                                <div class="input-field col s12">
                                    <table class="responsive-table">
                                        <thead>
                                            <tr>
                                                <td>
                                                    Items
                                                </td>
                                                <td>
                                                    Quantity
                                                </td>
                                                <td>
                                                    Monthly kWh
                                                </td>
                                            </tr>
                                        </thead>
                                        <tbody id="selectedItem1">                                                
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col s6">
                                <div class="input-field col s12">
                                    <select id="selectItem2" multiple onchange="getSelectedValue('2')">
                                        <option value="" disabled>Choose your option</option>
                                        <option value="Stereo">Stereo</option>
                                        <option value="TV-small(up_to_19)">TV - small (up to 19)</option>
                                        <option value="TV-medium(up_to_27)">TV - medium (up to 27)</option>
                                        <option value="TV-large(greater_than_27)">TV - large (greater than 27)</option>
                                        <option value="TV-27_LCD_Flat_Screen">TV - 27 LCD Flat Screen</option>
                                        <option value="TV-42Plasma">TV - 42 Plasma</option>
                                        <option value="VCR/DVD">VCR/DVD</option>
                                        <option value="Cable_box">Cable box</option>
                                        <option value="Satellite_dish">Satellite dish</option>
                                        <option value="Computer_and_printer">Computer and printer</option>
                                    </select>
                                    <label>Entertainment</label>
                                </div>
                            </div>
                            <div class="col s6">
                                <div class="input-field col s12">
                                    <table class="responsive-table">
                                        <thead>
                                            <tr>
                                                <td>
                                                    Items
                                                </td>
                                                <td>
                                                    Quantity
                                                </td>
                                                <td>
                                                    Monthly KWh
                                                </td>
                                            </tr>
                                        </thead>
                                        <tbody id="selectedItem2">                                                
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col s6">
                                <div class="input-field col s12">
                                    <select id="selectItem3" multiple onchange="getSelectedValue('3')">
                                        <option value="" disabled>Choose your option</option>
                                        <option value="Lighting_of_rooms">Lighting # of rooms</option>
                                        <option value="Outdoor_lighting_175W">Outdoor lighting 175W</option>
                                        <option value="Outdoor_lighting_250W">Outdoor lighting 250W</option>
                                    </select>
                                    <label>Lighting</label>
                                </div>
                            </div>
                            <div class="col s6">
                                <div class="input-field col s12">
                                    <table class="responsive-table">
                                        <thead>
                                            <tr>
                                                <td>
                                                    Items
                                                </td>
                                                <td>
                                                    Quantity
                                                </td>
                                                <td>
                                                    Monthly KWh
                                                </td>
                                            </tr>
                                        </thead>
                                        <tbody id="selectedItem3">
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col s6">
                                <div class="input-field col s12">
                                    <select id="selectItem4" multiple onchange="getSelectedValue('4')">
                                        <option value="" disabled>Choose your option</option>
                                        <option value="Water_Heater">Water Heater (# of bedrooms)</option>
                                        <option value="Electric_Dryer_of_loads_per_week">Electric Dryer # of loads per week</option>
                                        <option value="Washing_of_loads">Washing # of loads</option>
                                    </select>
                                    <label>Laundry</label>
                                </div>
                            </div>
                            <div class="col s6">
                                <div class="input-field col s12">
                                    <table class="responsive-table">
                                        <thead>
                                            <tr>
                                                <td>
                                                    Items
                                                </td>
                                                <td>
                                                    Quantity
                                                </td>
                                                <td>
                                                    Monthly KWh
                                                </td>
                                            </tr>
                                        </thead>
                                        <tbody id="selectedItem4">
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col s6">
                                <div class="input-field col s12">
                                    <select id="selectItem5" multiple onchange="getSelectedValue('5')">
                                        <option value="" disabled>Choose your option</option>
                                        <option value="Hot_Tub">Hot Tub</option>
                                        <option value="Pool_filter_pump">Pool filter / pump</option>
                                    </select>
                                    <label>Outdoor Equipment</label>
                                </div>
                            </div>
                            <div class="col s6">
                                <div class="input-field col s12">
                                    <table class="responsive-table">
                                        <thead>
                                            <tr>
                                                <td>
                                                    Items
                                                </td>
                                                <td>
                                                    Quantity
                                                </td>
                                                <td>
                                                    Monthly KWh
                                                </td>
                                            </tr>
                                        </thead>
                                        <tbody id="selectedItem5">
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col s6">
                                <div class="input-field col s12">
                                    <select id="selectItem6" multiple onchange="getSelectedValue('6')">
                                        <option value="" disabled>Choose your option</option>
                                        <option value="Dehumidifier">Dehumidifier</option>
                                        <option value="Humidifier">Humidifier</option>
                                        <option value="Air_Purifier">Air Purifier</option>
                                        <option value="Evaporative_Cooler">Evaporative Cooler</option>
                                        <option value="Window_Air_Conditioner">Window Air Conditioner</option>
                                        <option value="Ceiling_Fan">Ceiling Fan</option>
                                        <option value="Box_Fan">Box Fan</option>
                                        <option value="Electric_Blanket">Electric Blanket</option>
                                        <option value="Water_Bed_Heater">Water Bed Heater</option>
                                        <option value="Furnace_Fan">Furnace Fan</option>
                                        <option value="Furn_15KW_1100">Furn 15KW ~ 1100sq.ft</option>
                                        <option value="Furn_20KW_2000">Furn 20KW ~ 2000sq.ft</option>
                                        <option value="Furn_25KW_3000">Furn 25KW ~ 3000sq.ft</option>
                                        <option value="Bassboard_Lin_Feet">Bassboard Lin. Feet</option>
                                        <option value="Wall_Heaters_2000w">Wall Heaters @ 2000w</option>
                                        <option value="1500W_Portable">1500 W Portable</option>
                                        <option value="Heat_pump_fan">Heat pump fan</option>
                                        <option value="Heat_pump_800_1100">Heat pump 800 ~ 1100sq.ft</option>
                                        <option value="Heat_pump_1100_2000">Heat pump 1100 ~ 2000sq.ft</option>
                                        <option value="Heat_pump_2000_3000">Heat pump 2000 ~ 3000sq.ft</option>
                                        <option value="Air_Conditioner">Air Conditioner 1/2 ton</option>
                                        <option value="Air_Conditioner1.5_ton">Air Conditioner 1.5 ton</option>
                                        <option value="Air_Conditioner2_ton">Air Conditioner 2 ton</option>
                                        <option value="Air_Conditioner3_ton">Air Conditioner 3 ton</option>
                                        <option value="Air_Conditioner4_ton">Air Conditioner 4 ton</option>
                                        <option value="Air_Conditioner5_ton">Air Conditioner 5 ton</option>
                                    </select>
                                    <label>Comfort controls</label>
                                </div>
                            </div>
                            <div class="col s6">
                                <div class="input-field col s12">
                                    <table class="responsive-table">
                                        <thead>
                                            <tr>
                                                <td>
                                                    Items
                                                </td>
                                                <td>
                                                    Quantity
                                                </td>
                                                <td>
                                                    Monthly KWh
                                                </td>
                                            </tr>
                                        </thead>
                                        <tbody id="selectedItem6">                                                
                                        </tbody>
                                    </table>
                                </div>
                            </div><br><br>
                            <div class="row">
                                <div class="input-field col s12">
                                    <input id="average_bill1" name="average_bill1" type="text" placeholder=" " value="0">
                                    <label for="average_bill1">Yearly usage:  <span class="red-text lead">*</span></label>
                                    <input type="button" class="btn btn-primary" onclick="getTotal()" value="Calculate">
                                </div>
                            </div><br>
                        </div>
                        
                    </section>
                @endif
                @if(in_array('engineering permit package',$type))
                    <h6>Engineering Permit Package</h6>
                    <section>
                        <h4>Basic Information</h4>
                            <section>
                                <div class="row">
                                    <div class="col s12">
                                        <h6 class="red-text capitalize">* Mandatory Fields</h6>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col m4">
                                        <div class="input-field col s12">
                                            <div class="switch center">
                                                <label>
                                                    Max System Size
                                                    <input type="checkbox" id="hoa1" onclick="toggleSystemSize(this)">
                                                    <span class="lever"></span>
                                                    Limited System Size
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col m8">
                                        <div class="input-field col s12">
                                        <input type="hidden" name="eng_permit_package" id="engineering_permit_package" value="engineering_permit_package">
                                            <input id="system_size1" name="system_size" type="text" class="required" value="maximum" placeholder=" ">
                                            <label for="system_size">System Size</label>
            
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col m4">
                                        <div class="input-field col s12">
                                            <div class="switch center">
                                                <label class="tooltipped" data-position="top" data-delay="10" data-tooltip="House Owner Association">                         
                                                    HOA? &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;No
                                                    <input type="checkbox" onclick="toggleHOA(this)" checked>
                                                    <span class="lever"></span>
                                                    Yes
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col m4">
                                        <div class="input-field col s12">
                                            <select name="installation" id="installation1" >
                                                <option value="none" selected>None</option>
                                                <option value="front roof">Front Roof</option>
                                                <option value="black roof">Back Roof</option>
                                                <option value="garage">Garage</option>
                                            </select>
                                            <label for="installation">Installation restrictions</label>
                                        </div>
                                    </div>
                                    <div class="col m4">
                                        <div class="input-field col s12">
                                            <input id="remarks1" name="remarks" type="text"  value="" placeholder="If any">
                                            <label for="remarks">Remarks</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    @if($project_type == 'commercial')
                                    <div class="row">
                                        <div class="col s4">
                                            @component('components.autocomplete2', ["name" => "module", "data" => $moduleSelect])@endcomponent
                                        </div>
                                        <div class="col s4">
                                            <div class="input-field col s12">
                                                <input id="moduleType" name="moduleType" type="text"  value="" placeholder="Mention (watts)">
                                                <label for="moduleType">Module Type <span class="red-text lead">*</span></label>                                            
                                            </div>
                                        </div>
                                        <div class="col s4">
                                            <div class="input-field col s12" id="moduleOther_input2" style="display:none;">
                                                <input type="text" name="moduleOther" id="moduleOther2" value="moduleOther">
                                                <label for="moduleOther">Other  <span class="red-text lead">*</span></label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col s4">
                                            @component('components.autocomplete2', ["name" => "inverter", "data" => $inverterSelect])@endcomponent
                                        </div>
                                        <div class="col s4">
                                            <div class="input-field col s12">
                                                <input id="inverterType" name="inverterType" type="text"  value="" placeholder="Mention">
                                                <label for="inverterType">Inverter Type <span class="red-text lead">*</span></label>
                                            </div>
                                        </div>
                                        <div class="col s4">
                                            <div class="input-field col s12" id="inverterOther_input2" style="display:none;">
                                                <input type="text" name="inverterOther" id="inverterOther2" value="inverterOther">
                                                <label for="inverterOther">Other  <span class="red-text lead">*</span></label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col s4">
                                            @component('components.autocomplete2', ["name" => "racking", "data" => $rackingSelect])@endcomponent
                                        </div>
                                        <div class="col s4">
                                            <div class="input-field col s12">
                                                <input id="rackingType" name="rackingType" type="text"  value="" placeholder="Mention">
                                                <label for="rackingType">Racking Type <span class="red-text lead">*</span></label>
                                            </div>
                                        </div>
                                        <div class="col s4">
                                            <div class="input-field col s12" id="rackingOther_input2" style="display:none;">
                                                <input type="text" name="rackingOther" id="rackingOther2" value="rackingOther">
                                                <label for="rackingOther">Other  <span class="red-text lead">*</span></label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col s4">
                                            @component('components.autocomplete2', ["name" => "monitor", "data" => $monitorSelect])@endcomponent
                                        </div>
                                        <div class="col s4">
                                            <div class="input-field col s12">
                                                <input id="monitorType" name="monitorType" type="text"  value="" placeholder="Mention">
                                                <label for="monitorType">Monitor Type <span class="red-text lead">*</span></label>
                                            </div>
                                        </div>
                                        <div class="col s4">
                                            <div class="input-field col s12" id="monitorOther_input2" style="display:none;" >
                                                <input type="text" name="monitorOther" id="monitorOther2" value="monitorOther">
                                                <label for="monitorOther">Other  <span class="red-text lead">*</span></label>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                    @if($project_type == 'residential')
                                    <div class="row">
                                        <div class="col s4">
                                            @component('components.autocomplete2', ["name" => "module", "data" => $moduleSelect])@endcomponent
                                        </div>
                                        <div class="col s4">
                                            <div class="input-field col s12">
                                                <input id="moduleType" name="moduleType" type="text"  value="" placeholder="Mention (watts)">
                                                <label for="moduleType">Module Type <span class="red-text lead">*</span></label>                                            
                                            </div>
                                        </div>
                                        <div class="col s4">
                                            <div class="input-field col s12" id="moduleOther_input2" style="display:none;">
                                                <input type="text" name="moduleOther" id="moduleOther2" value="moduleOther">
                                                <label for="moduleOther">Other  <span class="red-text lead">*</span></label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col s4">
                                            @component('components.autocomplete2', ["name" => "inverter", "data" => $inverterSelect])@endcomponent
                                        </div>
                                        <div class="col s4">
                                            <div class="input-field col s12">
                                                <input id="inverterType" name="inverterType" type="text"  value="" placeholder="Mention">
                                                <label for="inverterType">Inverter Type <span class="red-text lead">*</span></label>
                                            </div>
                                        </div>
                                        <div class="col s4">
                                            <div class="input-field col s12" id="inverterOther_input2" style="display:none;">
                                                <input type="text" name="inverterOther" id="inverterOther2" value="inverterOther">
                                                <label for="inverterOther">Other  <span class="red-text lead">*</span></label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col s4">
                                            @component('components.autocomplete2', ["name" => "racking", "data" => $rackingSelect])@endcomponent
                                        </div>
                                        <div class="col s4">
                                            <div class="input-field col s12">
                                                <input id="rackingType" name="rackingType" type="text"  value="" placeholder="Mention">
                                                <label for="rackingType">Racking Type <span class="red-text lead">*</span></label>
                                            </div>
                                        </div>
                                        <div class="col s4">
                                            <div class="input-field col s12" id="rackingOther_input2" style="display:none;">
                                                <input type="text" name="rackingOther" id="rackingOther2" value="rackingOther">
                                                <label for="rackingOther">Other  <span class="red-text lead">*</span></label>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </section><hr>
                            <h4>Roof Information</h4>
                            <section>
                                <div class="row">
                                    <div class="col s12">
                                        <h6 class="red-text capitalize">* Mandatory Fields</h6>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col s6">
                                        <div class="input-field col s12">
                                            <input id="tree_cutting" name="tree_cutting" type="text"  value="" placeholder=" ">
                                            <label for="tree_cutting">Tree Cutting</label>
                                        </div>
                                    </div>
                                    <div class="col s6">
                                        <div class="input-field col s12">
                                            <input id="re_roofing" name="re_roofing" type="text"  value="" placeholder=" ">
                                            <label for="re_roofing">Re-Roofing</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col s6">
                                        <div class="input-field col s12">
                                            <div class="switch center">
                                                <label>
                                                    Service Upgrade&emsp;&emsp;No
                                                    <input type="checkbox" id="service_upgrade">
                                                    <span class="lever"></span>
                                                    Yes
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col s6">
                                        <div class="input-field col s12">
                                            <input id="others" name="others" type="text"  value="" placeholder=" ">
                                            <label for="others">Others</label>
                                        </div>
                                    </div>
                                </div>
                                <!-- <div class="row">
                                    <div class="col m4">
                                        <div class="input-field col s12">
                                            <p>
                                            <strong>Arrival</strong>
                                            </p>
                                            <p>
                                                <label>
                                                    <input type="checkbox" class="filled-in" />
                                                    <span>Screen shot of Calendar invite</span>
                                                </label>
                                            </p>
                                            <p>
                                                <label>
                                                    <input type="checkbox" class="filled-in" />
                                                    <span>Location of nearest Transformer</span>
                                                </label>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="col m4">
                                        <div class="input-field col s12">
                                            <p>
                                            <strong>Interior</strong>
                                            </p>
                                            <p>
                                                <label>
                                                    <input type="checkbox" class="filled-in" />
                                                    <span>Ceilings-existing cracks</span>
                                                </label>
                                            </p>
                                            <p>
                                                <label>
                                                    <input type="checkbox" class="filled-in" />
                                                    <span>Close up of breakers</span>
                                                </label>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="col m4">
                                        <div class="input-field col s12">
                                            <p>
                                            <strong>Attic</strong>
                                            </p>
                                            <p>
                                                <label>
                                                    <input type="checkbox" class="filled-in" />
                                                    <span>Sheating size, stamp if possible</span>
                                                </label>
                                            </p>
                                            <p>
                                                <label>
                                                    <input type="checkbox" class="filled-in" />
                                                    <span>Pitch on rafter of each roof plane</span>
                                                </label>
                                            </p>
                                        </div>
                                    </div>
                                </div> -->
                                <div class="row image-repeater">
                                    <div data-repeater-list="repeater-group" id="repeater-group">
                                        <div data-repeater-item class="row">
                                            <div class="input-field col s4">
                                                <img src="{{ asset('assets/images/big/roof.jpg') }}" class="materialboxed" width="320px" height="150px" >
                                            </div>
                                            <div class="input-field col s2" style="margin-top:5%;">
                                                <input id="overhang" name="overhang[]" type="text" class="required" placeholder="(feet)">
                                                <label for="overhang">Overhang (A)  <span class="red-text lead">*</span></label>
                                            </div>
                                            <div class="input-field col s2" style="margin-top:5%;">
                                                <input id="width" name="width[]" type="text" class="required" placeholder="(feet)">
                                                <label for="width">Width (B)  <span class="red-text lead">*</span></label>
                                            </div>
                                            <div class="input-field col s2" style="margin-top:5%;">
                                                <input id="height" name="height[]" type="text" class="required" placeholder="(feet)">
                                                <label for="height">Height (C)  <span class="red-text lead">*</span></label>
                                            </div>
                                            <div class="input-field col s1" style="margin-top:5%;">
                                                <button data-repeater-delete="" class="btn btn-small red" type="button"><i class="material-icons">clear</i></button>
                                            </div>
                                        </div>
                                    </div>
                                    <button type="button" data-repeater-create="" class="btn btn-small indigo m-l-10">Add Roof</button>
                                </div>
                                <div class="row"><br>
                                    <div class="left-align">Roof Decking/Layer</div>
                                    <div class="row center-align">
                                        <div class="input-field col s3">
                                            <p>
                                                <label>
                                                    <input type="checkbox" class="filled-in" id="plywood"/>
                                                    <span>Plywood</span>
                                                </label>
                                            </p>
                                        </div>
                                        <div class="input-field col s3">
                                            <p>
                                                <label>
                                                    <input type="checkbox" class="filled-in" id="osb"/>
                                                    <span>OSB</span>
                                                </label>
                                            </p>
                                        </div>
                                        <div class="input-field col s3">
                                            <p>
                                                <label>
                                                    <input type="checkbox" class="filled-in" id="skip_sheating"/>
                                                    <span>Skip Sheating</span>
                                                </label>
                                            </p>
                                        </div>
                                        <div class="input-field col s3">
                                            <p>
                                                <label>
                                                    <input type="checkbox" class="filled-in" id="plank"/>
                                                    <span>Plank</span>
                                                </label>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="input-field col s12">
                                        <input id="roofDecking_LayerThickness" name="roofDecking_LayerThickness" type="text"  value="" placeholder=" ">
                                        <label for="roofDecking_LayerThickness">Roof Decking/Layer Thickness</label>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="input-field col s6">
                                        <input id="center_spacing" name="center_spacing" type="text" class="required" placeholder="(inches)">
                                        <label for="center_spacing">On-Center Spacing  <span class="red-text lead">*</span></label>
        
                                    </div>
                                    <div class="input-field col s6">
                                        <input id="purlin" name="purlin" type="text" class="required" placeholder="(inches)">
                                        <label for="purlin">Purlin/Support Structure Sizes, Spacing, Span, Notes  <span class="red-text lead">*</span></label>        
                                    </div>
                                </div>
                                <div class="row"><br>
                                    <div class="left-align">Describe Access to Attic</div><br>
                                    <div class="row">
                                        <div class="col s4 input-field">
                                            <input id="pitch" name="pitch" validate="pitch" type="number" onblur="checkVal()" value="0">
                                            <label for="pitch">Pitch </label>
                                            <span class="helper-text red-text" id="pitch_error"></span>
                                        </div>
                                        <div class="input-field col s4">
                                            <input id="azimuth" name="azimuth" validate="azimuth" type="number" onblur="checkVal()" value="0">
                                            <label for="azimuth">Azimuth </label>
                                            <span class="helper-text red-text" id="azimuth_error"></span>
                                        </div>
                                        <div class="input-field col s4">
                                            <select name="rafter_size" id="rafter_size">
                                                <option value="" disabled selected>Choose your option</option>
                                                <option value="2x4">2x4</option>
                                                <option value="2x6">2x6</option>
                                                <option value="2x8">2x8</option>
                                                <option value="4x6">4x6</option>
                                                <option value="4x8">4x8</option>
                                            </select>
                                            <label for="rafter_size">Rafter Size </label>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="input-field col s4">
                                            <select id="roofMaterialOption" name="roofMaterialOption" onchange="check(this.value);">
                                                <option value="" disabled selected>Choose your option</option>
                                                <option value="Asphalt">Asphalt</option>
                                                <option value="Metal">Metal</option>
                                                <option value="Shingle">Shingle</option>
                                                <option value="Tile">Tile</option>
                                                <option value="Others">Others</option>
                                            </select>
                                            <label for="roof_material">Roof Material </label>
                                        </div>
                                        <div class="input-field col s4" id="other_roof_material_input" style="display:none;">
                                            <input id="other_roof_material" name="other_roof_material" type="text" class="required" placeholder=" ">
                                            <label for="other_roof_material">Other Roof Material </label>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="input-field col s3">
                                            <div class="switch center">
                                                <label>
                                                    Soft Spots&emsp;&emsp;No
                                                    <input type="checkbox" id="soft_spots">
                                                    <span class="lever"></span>
                                                    Yes
                                                </label>
                                            </div>
                                        </div>
                                        <div class="input-field col s3">
                                            <div class="switch center">
                                                <label>
                                                    Bouncy&emsp;&emsp;No
                                                    <input type="checkbox" id="bouncy">
                                                    <span class="lever"></span>
                                                    Yes
                                                </label>
                                            </div>
                                        </div>
                                        <div class="input-field col s3">
                                            <div class="switch center">
                                                <label>
                                                    Existing Leaks&emsp;&emsp;No
                                                    <input type="checkbox" id="existing_leaks">
                                                    <span class="lever"></span>
                                                    Yes
                                                </label>
                                            </div>
                                        </div>
                                        <div class="input-field col s3">
                                            <div class="switch center">
                                                <label>
                                                    Vaulted Ceiling&emsp;&emsp;No
                                                    <input type="checkbox" id="vaulted_ceiling">
                                                    <span class="lever"></span>
                                                    Yes
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="input-field col s6">
                                        <select name="comp_shingle_layers" id="comp_shingle_layers">
                                            <option value="" disabled selected>Choose your option</option>
                                            <option value="1">1</option>
                                            <option value="2">2</option>
                                            <option value="3">3</option>
                                            <option value="more than 3">More than 3</option>
                                        </select>
                                        <label for="comp_shingle_layers">If Comp Shingle how many layers </label>
        
                                    </div>
                                    <div class="input-field col s6">
                                        <input id="age_of_shingles" name="age_of_shingles" type="text" class="required" placeholder="(years)">
                                        <label for="age_of_shingles">Age of Shingles  <span class="red-text lead">*</span></label>
        
                                    </div>
                                    <div class="col s6">
                                        <div class="input-field">
                                            <div class="switch center">
                                                <label>
                                                    Roof Condition&emsp;&emsp;Bad
                                                    <input type="checkbox" id="roof_condition">
                                                    <span class="lever"></span>
                                                    Good
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col m4">
                                            <div class="input-field col s12">
                                                <p>
                                                <strong>Finished or Valued ceiling protocol</strong>
                                                </p>
                                                <p>
                                                    <label>
                                                        <input type="checkbox" class="filled-in" id="access_attic_vent"/>
                                                        <span>Access from attic vent?</span>
                                                    </label>
                                                </p>
                                                <p>
                                                    <label>
                                                        <input type="checkbox" class="filled-in" id="stud_finder"/>
                                                        <span>Stud finder</span>
                                                    </label>
                                                </p>
                                            </div>
                                        </div>
                                    <!-- <div class="col m4">
                                        <div class="input-field col s12">
                                            <p>
                                            <strong>Roof</strong>
                                            </p>
                                            <p>
                                                <label>
                                                    <input type="checkbox" class="filled-in" />
                                                    <span>360&#176; skyline</span>
                                                </label>
                                            </p>
                                            <p>
                                                <label>
                                                    <input type="checkbox" class="filled-in" />
                                                    <span>Pliability test</span>
                                                </label>
                                            </p>
                                        </div>
                                    </div> -->
                                    </div>
                                </div>
                            </section><hr>
                            <h4>Electrical Information</h4>
                            <section>
                                <div class="row">
                                    <div class="col s12">
                                        <h6 class="red-text capitalize">* Mandatory Fields</h6>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="input-field col s12">
                                        <input id="utility" name="utility" type="text"  value="" placeholder=" ">
                                        <label for="utility">Utility</label>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="input-field col s3">
                                        <input id="supply_side_voltage" name="supply_side_voltage" type="text" placeholder=" ">
                                        <label for="supply_side_voltage">Supply Side Voltage </label>
        
                                    </div>
                                    <div class="input-field col s3">
                                        <input id="manufacturer_model" name="manufacturer_model" type="text" placeholder=" ">
                                        <label for="manufacturer_model">Manufacturer and Model </label>
        
                                    </div>
                                    <div class="input-field col s3">
                                        <input id="main_breaker_rating" name="main_breaker_rating" type="text" class="required" placeholder=" ">
                                        <label for="main_breaker_rating">Main Breaker Rating  <span class="red-text lead">*</span></label>
        
                                    </div>
                                    <div class="input-field col s3">
                                        <input id="busbar_rating" name="busbar_rating" type="text" class="required" placeholder=" ">
                                        <label for="busbar_rating">Busbar Rating  <span class="red-text lead">*</span></label>
        
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="input-field col s3">
                                        <input id="meter_no" name="meter_no" type="text" placeholder=" ">
                                        <label for="meter_no">Meter No </label>
        
                                    </div>
                                    <div class="input-field col s3">
                                        <input id="proposed_point_connection" name="proposed_point_connection" type="text" placeholder=" ">
                                        <label for="proposed_point_connection">Proposed Point of Connection </label>
        
                                    </div>
                                    <div class="input-field col s3">
                                        <input id="meter_location" name="meter_location" type="text" class="required" placeholder=" ">
                                        <label for="meter_location">Meter Location  <span class="red-text lead">*</span></label>
        
                                    </div>
                                    <div class="input-field col s3">
                                        <div class="switch center">
                                            <label>
                                                Tap Possible&emsp;&emsp;No
                                                <input type="checkbox" id="tap_possible">
                                                <span class="lever"></span>
                                                Yes
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="input-field col s3">
                                        <input id="breaker_space" name="breaker_space" type="text" class="required" placeholder=" ">
                                        <label for="breaker_space">Breaker Space  <span class="red-text lead">*</span></label>
        
                                    </div>
                                    <div class="input-field col s3">
                                        <input id="grounding_method" name="grounding_method" type="text" class="required" placeholder=" ">
                                        <label for="grounding_method">Grounding Method  <span class="red-text lead">*</span></label>
        
                                    </div>
                                    <div class="input-field col s3">
                                        <input id="disconnect_type" name="disconnect_type" type="text" class="required" placeholder=" ">
                                        <label for="disconnect_type">Disconnect Type  <span class="red-text lead">*</span></label>
        
                                    </div>
                                    <div class="input-field col s3">
                                        <input id="panel_location" name="panel_location" type="text" class="required" placeholder=" ">
                                        <label for="panel_location">Panel Location  <span class="red-text lead">*</span></label>
        
                                    </div>
                                </div>
                                <div class="row panel-repeater">
                                    <div data-repeater-list="repeater-group">
                                        <div data-repeater-item class="row">
                                            <div class="input-field col s2">
                                                <label>Sub Panel</label>
                                            </div>
                                            <div class="input-field col s3">
                                                <input id="manufacturer_model1" name="manufacturer_model1[]" class="required" type="text" placeholder="">
                                                <label for="manufacturer_model1">Manufacturer and Model  <span class="red-text lead">*</span></label>
                                            </div>
                                            <div class="input-field col s3">
                                                <input id="main_breaker_rating1" name="main_breaker_rating1[]"type="text" placeholder="">
                                                <label for="main_breaker_rating1">Main Breaker Rating</label>
                                            </div>
                                            <div class="input-field col s3">
                                                <input id="busbar_rating1" name="busbar_rating1[]"type="text" placeholder="">
                                                <label for="busbar_rating1">Busbar Rating</label>
                                            </div>
                                            <div class="input-field col s1">
                                                <button data-repeater-delete="" class="btn btn-small" type="button"><i class="material-icons">clear</i></button>
                                            </div>
                                        </div>
                                    </div>
                                    <button type="button" data-repeater-create="" class="btn btn-small m-l-10">Add Sub Panel</button>
                                </div>
                            </section><hr>
                            <h4>Utility Bills/Electrical Load</h4>
                            <section>
                                <div class="row">
                                    <div class="col s12">
                                        <h6 class="red-text capitalize">* Mandatory Fields</h6>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col s12">
                                        <div class="mh-a" id="uppyBill"></div>
                                        <div class="center">
                                            <span class="helper-text imperial-red-text" id="files_error"></span>
                                        </div>
                                    </div>
                                </div><br>
                                <h3 class="center-align">- OR - </h3><br>
                                <div class="row">
                                    <div class="input-field col s12">
                                        <input id="average_bill" name="average_bill" type="text" placeholder=" ">
                                        <label for="average_bill">Yearly usage </label>
                                    </div>
                                </div><br>
                                <h3 class="center-align">- OR - </h3><br>
                                <div class="row">
                                    <div class="col s6">
                                        <div class="input-field col s12">
                                            <select id="selectItem1" multiple onchange="getSelectedValue('1')">
                                                <option value="" disabled>Choose your option</option>
                                                <option value="Refrigerator_w_freezer">Refrigerator w/freezer</option>
                                                <option value="Freezer_Chest">Freezer - Chest</option>
                                                <option value="Freezer_Upright">Freezer - Upright</option>
                                                <option value="Dishwasher">Dishwasher</option>
                                                <option value="Range">Range</option>
                                                <option value="Oven">Oven</option>
                                                <option value="Microwave">Microwave</option>
                                                <option value="Toaster_oven">Toaster oven</option>
                                                <option value="Coffee_maker">Coffee maker</option>
                                                <option value="Garbage_disposal">Garbage disposal</option>
                                                <option value="Well_pump_1_2_HP">Well pump 1/2 HP</option>
                                            </select>
                                            <label>Kitchen</label>
                                        </div>
                                    </div>
                                    <div class="col s6">
                                        <div class="input-field col s12">
                                            <table class="responsive-table">
                                                <thead>
                                                    <tr>
                                                        <td>
                                                            Items
                                                        </td>
                                                        <td>
                                                            Quantity
                                                        </td>
                                                        <td>
                                                            Monthly kWh
                                                        </td>
                                                    </tr>
                                                </thead>
                                                <tbody id="selectedItem1">                                                
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col s6">
                                        <div class="input-field col s12">
                                            <select id="selectItem2" multiple onchange="getSelectedValue('2')">
                                                <option value="" disabled>Choose your option</option>
                                                <option value="Stereo">Stereo</option>
                                                <option value="TV_small_up_to_19">TV - small (up to 19)</option>
                                                <option value="TV_medium_up_to_27">TV - medium (up to 27)</option>
                                                <option value="TV_large_greater_than_27">TV - large (greater than 27)</option>
                                                <option value="TV_27_LCD_Flat_Screen">TV - 27 LCD Flat Screen</option>
                                                <option value="TV_42Plasma">TV - 42 Plasma</option>
                                                <option value="VCR_DVD">VCR/DVD</option>
                                                <option value="Cable_box">Cable box</option>
                                                <option value="Satellite_dish">Satellite dish</option>
                                                <option value="Computer_and_printer">Computer and printer</option>
                                            </select>
                                            <label>Entertainment</label>
                                        </div>
                                    </div>
                                    <div class="col s6">
                                        <div class="input-field col s12">
                                            <table class="responsive-table">
                                                <thead>
                                                    <tr>
                                                        <td>
                                                            Items
                                                        </td>
                                                        <td>
                                                            Quantity
                                                        </td>
                                                        <td>
                                                            Monthly KWh
                                                        </td>
                                                    </tr>
                                                </thead>
                                                <tbody id="selectedItem2">                                                
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col s6">
                                        <div class="input-field col s12">
                                            <select id="selectItem3" multiple onchange="getSelectedValue('3')">
                                                <option value="" disabled>Choose your option</option>
                                                <option value="Lighting_of_rooms">Lighting # of rooms</option>
                                                <option value="Outdoor_lighting_175W">Outdoor lighting 175W</option>
                                                <option value="Outdoor_lighting_250W">Outdoor lighting 250W</option>
                                            </select>
                                            <label>Lighting</label>
                                        </div>
                                    </div>
                                    <div class="col s6">
                                        <div class="input-field col s12">
                                            <table class="responsive-table">
                                                <thead>
                                                    <tr>
                                                        <td>
                                                            Items
                                                        </td>
                                                        <td>
                                                            Quantity
                                                        </td>
                                                        <td>
                                                            Monthly KWh
                                                        </td>
                                                    </tr>
                                                </thead>
                                                <tbody id="selectedItem3">
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col s6">
                                        <div class="input-field col s12">
                                            <select id="selectItem4" multiple onchange="getSelectedValue('4')">
                                                <option value="" disabled>Choose your option</option>
                                                <option value="Water_Heater">Water Heater (# of bedrooms)</option>
                                                <option value="Electric_Dryer_of_loads_per_week">Electric Dryer # of loads per week</option>
                                                <option value="Washing_of_loads">Washing # of loads</option>
                                            </select>
                                            <label>Laundry</label>
                                        </div>
                                    </div>
                                    <div class="col s6">
                                        <div class="input-field col s12">
                                            <table class="responsive-table">
                                                <thead>
                                                    <tr>
                                                        <td>
                                                            Items
                                                        </td>
                                                        <td>
                                                            Quantity
                                                        </td>
                                                        <td>
                                                            Monthly KWh
                                                        </td>
                                                    </tr>
                                                </thead>
                                                <tbody id="selectedItem4">
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col s6">
                                        <div class="input-field col s12">
                                            <select id="selectItem5" multiple onchange="getSelectedValue('5')">
                                                <option value="" disabled>Choose your option</option>
                                                <option value="Hot_Tub">Hot Tub</option>
                                                <option value="Pool_filter_pump">Pool filter / pump</option>
                                            </select>
                                            <label>Outdoor Equipment</label>
                                        </div>
                                    </div>
                                    <div class="col s6">
                                        <div class="input-field col s12">
                                            <table class="responsive-table">
                                                <thead>
                                                    <tr>
                                                        <td>
                                                            Items
                                                        </td>
                                                        <td>
                                                            Quantity
                                                        </td>
                                                        <td>
                                                            Monthly KWh
                                                        </td>
                                                    </tr>
                                                </thead>
                                                <tbody id="selectedItem5">
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col s6">
                                        <div class="input-field col s12">
                                            <select id="selectItem6" multiple onchange="getSelectedValue('6')">
                                                <option value="" disabled>Choose your option</option>
                                                <option value="Dehumidifier">Dehumidifier</option>
                                                <option value="Humidifier">Humidifier</option>
                                                <option value="Air_Purifier">Air Purifier</option>
                                                <option value="Evaporative_Cooler">Evaporative Cooler</option>
                                                <option value="Window_Air_Conditioner">Window Air Conditioner</option>
                                                <option value="Ceiling_Fan">Ceiling Fan</option>
                                                <option value="Box_Fan">Box Fan</option>
                                                <option value="Electric_Blanket">Electric Blanket</option>
                                                <option value="Water_Bed_Heater">Water Bed Heater</option>
                                                <option value="Furnace_Fan">Furnace Fan</option>
                                                <option value="Furn_15KW_1100">Furn 15KW ~ 1100sq.ft</option>
                                                <option value="Furn_20KW_2000">Furn 20KW ~ 2000sq.ft</option>
                                                <option value="Furn_25KW_3000">Furn 25KW ~ 3000sq.ft</option>
                                                <option value="Bassboard_Lin_Feet">Bassboard Lin. Feet</option>
                                                <option value="Wall_Heaters_2000w">Wall Heaters @ 2000w</option>
                                                <option value="1500W_Portable">1500 W Portable</option>
                                                <option value="Heat_pump_fan">Heat pump fan</option>
                                                <option value="Heat_pump_800_1100">Heat pump 800 ~ 1100sq.ft</option>
                                                <option value="Heat_pump_1100_2000">Heat pump 1100 ~ 2000sq.ft</option>
                                                <option value="Heat_pump_2000_3000">Heat pump 2000 ~ 3000sq.ft</option>
                                                <option value="Air_Conditioner">Air Conditioner 1/2 ton</option>
                                                <option value="Air_Conditioner1_5_ton">Air Conditioner 1.5 ton</option>
                                                <option value="Air_Conditioner2_ton">Air Conditioner 2 ton</option>
                                                <option value="Air_Conditioner3_ton">Air Conditioner 3 ton</option>
                                                <option value="Air_Conditioner4_ton">Air Conditioner 4 ton</option>
                                                <option value="Air_Conditioner5_ton">Air Conditioner 5 ton</option>
                                            </select>
                                            <label>Comfort controls</label>
                                        </div>
                                    </div>
                                    <div class="col s6">
                                        <div class="input-field col s12">
                                            <table class="responsive-table">
                                                <thead>
                                                    <tr>
                                                        <td>
                                                            Items
                                                        </td>
                                                        <td>
                                                            Quantity
                                                        </td>
                                                        <td>
                                                            Monthly KWh
                                                        </td>
                                                    </tr>
                                                </thead>
                                                <tbody id="selectedItem6">                                                
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <br>
                                    <br>
                                    <div class="row">
                                        <div class="input-field col s12">
                                            <input id="average_bill1" name="average_bill1" type="text" placeholder=" " value="0">
                                            <label for="average_bill1">Yearly usage  <span class="red-text lead">*</span></label>
                                            <input type="button" class="btn btn-primary" onclick="getTotal()" value="Calculate">
                                        </div>
                                    </div>
                                </div>
                            </section><hr>
                            <h4>Upload Supporting Documents</h4>
                            <section>
                                <div class="row">
                                    <div class="col s9">
                                        <div class="mh-a" id="uppySupportingDocuments1"></div>
                                        <div class="center">
                                            <span class="helper-text imperial-red-text" id="files_error"></span>
                                        </div>
                                    </div>
                                    <div class="col s3">
                                        <a href="{{ asset('assets/document/Site_Survey_Document.pdf') }}" target="_blank">
                                            <button type="button" class="btn btn-small indigo tooltipped" data-position="bottom" data-tooltip="Click here to view Sample Upload"><i class="ti-cloud-down left"></i>Sample Upload</button>
                                        </a>
                                    </div>
                                </div>
                                    {{-- <div class="row">
                                        <div class="col s12">
                                            @if(Auth::user()->role == 'admin' || Auth::user()->role == 'customer')
                                                <x-DesignCostAddition :projectID=$project_id :design=$type></x-DesignCostAddition>
                                            @endif
                                        </div>
                                    </div> --}}
                               
                                <div class="row">
                                    <div class="col s12 m4 offset-m4" id="stripe_card" style="display: none">
                                        <div class="card-panel center prussian-blue" style="color:#fff;">
                                            <h5 id="stripe_error" class="white-text"></h5>
                                            <h6  class="white-text">Try again later or add / change your default payment method</h6>
                                        </div>
                                    </div>
                                </div>
                            </section>
                    </section>
                @endif
                    <h6>Payment Details</h6>
                    <section>
                        <div class="row">
                            <x-DesignCostAddition :projectID=$project_id :design=$type></x-DesignCostAddition>
                        </div>
                        <div class="row">
                            <div class="col s12 m4 offset-m4" id="stripe_card" style="display: none">
                                <div class="card-panel center prussian-blue" style="color:#fff;">
                                    <h5 id="stripe_error" class="white-text"></h5>
                                    <h6  class="white-text">Try again later or add / change your default payment method</h6>
                                </div>
                            </div>
                        </div>
                    </section>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
    <script src="{{ asset('assets/libs/jquery-steps/build/jquery.steps.min.js') }}"></script>
    <script src="{{ asset('assets/libs/jquery-validation/dist/jquery.validate.min.js') }}"></script>
    <script>
    var form = $(".validation-wizard").show();

    $(".validation-wizard").steps({
        headerTag: "h6",
        bodyTag: "section",
        transitionEffect: "fade",
        titleTemplate: '<span class="step">#index#</span> #title#',
        labels: {
            finish: "Submit"
        },
        onStepChanging: function(event, currentIndex, newIndex) {
            return currentIndex > newIndex || !(3 === newIndex && Number($("#age-2").val()) < 18) && (currentIndex < newIndex && (form.find(".body:eq(" + newIndex + ") label.error").remove(), form.find(".body:eq(" + newIndex + ") .error").removeClass("error")), form.validate().settings.ignore = ":disabled,:hidden", form.valid())
        },
        onFinishing: function(event, currentIndex) {
            insert(event);
            
        },
        onFinished: function(event, currentIndex) {
        
        }
    }), $(".validation-wizard").validate({
        ignore: "input[type=hidden]",
        errorClass: "red-text",
        successClass: "green-text",
        highlight: function(element, errorClass) {
            $(element).removeClass(errorClass)
        },
        unhighlight: function(element, errorClass) {
            $(element).removeClass(errorClass)
        },
        errorPlacement: function(error, element) {
            error.insertAfter(element)
        },
        rules: {
            email: {
                email: !0
            }
        }
    });

        function toggleSystemSize(elem) {
            if (elem.checked) {
                document.getElementById('system_size').disabled = false;
                document.getElementById('system_size').value = "";
            } else {
                document.getElementById('system_size').disabled = true;
                document.getElementById('system_size').value = "maximum";
            }
            M.updateTextFields();
        }

        function toggleHOA(elem) {
            if (elem.checked) {
                document.getElementById('installation').disabled = false;
                document.getElementById('installation').value = "none";

                document.getElementById('remarks').disabled = false;
                document.getElementById('remarks').value = "";
            } else {
                document.getElementById('installation').disabled = true;
                document.getElementById('installation').value = "none";

                document.getElementById('remarks').disabled = true;
                document.getElementById('remarks').value = "none";
            }
            M.FormSelect.init(document.querySelector("#installation"));
            M.updateTextFields();
        }

        function validateFields() {

           
            let form = document.forms["array_form"].getElementsByTagName("input");
           console.log("<--- Form Data --->  : ",form);
            let errors =  0;
            let jsonData = {};
            //Make the thing green
            function right(item) {
                item.classList.remove("invalid");
                item.classList.add("valid");

                if (item.getAttribute("name"))
                    jsonData[item.getAttribute("name")] = item.value;
            }

            //Make the thing red
            function wrong(item) {
                item.classList.remove("valid");
                item.classList.add("invalid");
                errors++;
            }

            // All the non-select inputs
            for (let item of form) {
                if (item.getAttribute('validate') === 'tilt') {
                    if (item.value >= 0 && item.value <= 90)
                        right(item)
                    else
                        wrong(item)
                } else if (item.getAttribute('validate') === 'azimuth') {
                    if (item.value >= 0 && item.value <= 360)
                        right(item)
                    else
                        wrong(item)
                } else if (item.getAttribute('validate') === 'panels') {
                    if (item.value >= 1)
                        right(item)
                    else
                        wrong(item)
                } else if (item.getAttribute('validate') === 'offset') {
                    if (item.value >= 1 && item.value <= 200)
                        right(item)
                    else
                        wrong(item)
                }else if(item.getAttribute('id') === 'aurora_design'){
                   
                    aurora_fields();
                }
                else if(item.getAttribute('id') === 'structural_load_letter_and_calculations')
                {
                    
                    structural_load();
                }
                else if(item.getAttribute('id') === 'engineering_permit_package')
                {
                    
                    eng_permit_package();
                }
                else {
                    if (!validate.single(item.value, {presence: {allowEmpty: false}}))
                        right(item);
                //     else
                //         wrong(item);
                }
            }


            function eng_permit_package()
            {
                const overhang = document.getElementById('overhang').value;
                const width = document.getElementById('width').value;
                const height = document.getElementById('height').value;
                const manufacturer_model = document.getElementById('manufacturer_model1').value;
                const fields = {arrays: [], overhang: overhang[0], width: width[0], height: height[0], manufacturer_model: manufacturer_model[0]};

                for(const key in arrays) {
                    fields.arrays.push(arrays[key]);
                    console.log(arrays[key]);
                }
                jsonData["array"] = fields;

                M.FormSelect.init(document.querySelector("#installation1"));
            M.FormSelect.init(document.querySelector("#roofMaterialOption"));
            M.FormSelect.init(document.querySelector("#comp_shingle_layers"));
            M.FormSelect.init(document.querySelector("#rafter_size"));
           
            const installation = M.FormSelect.getInstance(document.querySelector("#installation1"));
                jsonData["installation"] = installation.getSelectedValues()[0];
            
            const roofMaterialOption = M.FormSelect.getInstance(document.querySelector("#roofMaterialOption"));
                jsonData["roofMaterialOption"] = roofMaterialOption.getSelectedValues()[0];
            
            const comp_shingle_layers = M.FormSelect.getInstance(document.querySelector("#comp_shingle_layers"));
                jsonData["comp_shingle_layers"] = comp_shingle_layers.getSelectedValues()[0];

            const rafter_size = M.FormSelect.getInstance(document.querySelector("#rafter_size"));
                jsonData["rafter_size"] = rafter_size.getSelectedValues()[0];

                jsonData["hoa"] = document.getElementById('hoa1').checked;
            jsonData["service_upgrade"] = document.getElementById('service_upgrade').checked;
            jsonData["plywood"] = document.getElementById('plywood').checked;
            jsonData["osb"] = document.getElementById('osb').checked;
            jsonData["skip_sheating"] = document.getElementById('skip_sheating').checked;
            jsonData["plank"] = document.getElementById('plank').checked;
            jsonData["soft_spots"] = document.getElementById('soft_spots').checked;
            jsonData["bouncy"] = document.getElementById('bouncy').checked;
            jsonData["existing_leaks"] = document.getElementById('existing_leaks').checked;
            jsonData["vaulted_ceiling"] = document.getElementById('vaulted_ceiling').checked;
            jsonData["roof_condition"] = document.getElementById('roof_condition').checked;
            jsonData["access_attic_vent"] = document.getElementById('access_attic_vent').checked;
            jsonData["stud_finder"] = document.getElementById('stud_finder').checked;
            jsonData["tap_possible"] = document.getElementById('tap_possible').checked;
            jsonData["project_id"] = "{{$project_id}}";
            
            }
            function aurora_fields()
            {

            const inverterOther = document.getElementById('inverterOther');
            // const monitorOther = document.getElementById('monitorOther');
            const moduleOther = document.getElementById('moduleOther');
            const rackingOther = document.getElementById('rackingOther');

            if(inverterOther.value !== "")
                right(inverterOther);
            else{
                inverterOther.classList.value = "valid";
                jsonData[inverterOther.getAttribute("name")] = "No inverter";
            }

            M.FormSelect.init(document.querySelector("#installation"));
            const installation = M.FormSelect.getInstance(document.querySelector("#installation"));
            if (installation.getSelectedValues()[0] === "") wrong(installation.wrapper);
            else {
                right(installation.wrapper);
                jsonData["installation"] = installation.getSelectedValues()[0];
            }

            jsonData["hoa"] = document.getElementById('hoa').checked;


            const notes = document.getElementById('notes');
            if (notes.value !== ""){
                //alert("notes");
                right(notes);
            }
            else
                jsonData[notes.getAttribute("name")] = "No notes";
            // if(monitorOther.value !== "")
            //     right(monitorOther);
            // else
            //     jsonData[monitorOther.getAttribute("name")] = "No monitor";

            if(moduleOther.value !== ""){
                //alert("Module");
                right(moduleOther);
            }else{
                moduleOther.classList.value = "valid";
                jsonData[moduleOther.getAttribute("name")] = "No module";
            }

            if(rackingOther.value !== "")
                right(rackingOther);
            else{
                rackingOther.classList.value = "valid";
                jsonData[rackingOther.getAttribute("name")] = "No racking";
            }
        }

            function structural_load()
            {
                const inverterOther1 = document.getElementById('inverterOther1');
            // const monitorOther = document.getElementById('monitorOther');
            const moduleOther1 = document.getElementById('moduleOther1');
            const rackingOther1 = document.getElementById('rackingOther1');

            if(inverterOther1.value !== "")
                right(inverterOther1);
            else{
                inverterOther1.classList.value = "valid";
                jsonData[inverterOther1.getAttribute("name")] = "No inverter";
            }

            
            if(moduleOther1.value !== ""){
                //alert("Module");
                right(moduleOther1);
            }else{
                moduleOther1.classList.value = "valid";
                jsonData[moduleOther1.getAttribute("name")] = "No module";
            }

            if(rackingOther1.value !== "")
                right(rackingOther1);
            else{
                rackingOther1.classList.value = "valid";
                jsonData[rackingOther1.getAttribute("name")] = "No racking";
            }
            const roofType = M.FormSelect.getInstance(document.querySelector("#roof_type"));
            const fields = {arrays: [],roofType: roofType.getSelectedValues()[0]};
            for (const key in arrays) {
                fields.arrays.push(arrays[key]);
            }
            jsonData['fields']=fields;

            }
                       

            jsonData['stripe_payment_aurora']="no";
            jsonData['stripe_payment_stamping']="no";
            jsonData['stripe_payment_structural']="no";
            jsonData['stripe_payment_electrical']="no";
            jsonData['stripe_payment_permit']="no";
            jsonData['average_bill']="";
            return {
                errors: errors,
                columns: jsonData
            };
            }
            let arrays = {};
            document.getElementById('add_array').addEventListener('click', function () {
            const result = validateFields()
            if (!result.errors) {
                let row = document.createElement('tr');
                row.id = Date.now();
                arrays[row.id] = result.columns;
                row.innerHTML = `<td class="center" data-name="module">${result.columns.module}</td>
                                <td class="center" data-name="inverter">${result.columns.inverter}</td> 
                            @if($project_type == 'commercial')
                                <td class="center" data-name="racking">${result.columns.racking}</td>
                                <td class="center" data-name="monitor">${result.columns.monitor}</td>
                            @else
                                <td class="center" data-name="racking">${result.columns.racking}</td>
                            @endif
                                <td class="center" data-name="panels">${result.columns.panels}</td>
                                <td class="center" data-name="tilt">${result.columns.tilt}</td>
                                <td class="center" data-name="azimuth">${result.columns.azimuth}</td>
                                <td class="center"><button type="button" class="btn imperial-red-outline-button remove" data-id="${row.id}" onclick="remove(this)">Remove</button></td>`;
                document.getElementById('array_table').append(row);
            }
        });

            function remove(elem) {
                const id = elem.getAttribute('data-id')
                document.getElementById(id).remove();
                delete arrays[id];
            }

        function getTotal(){
            total=0;
            itemList=[];
            itemList.push($("#selectItem1").val());
            itemList.push($("#selectItem2").val());
            itemList.push($("#selectItem3").val());
            itemList.push($("#selectItem4").val());
            itemList.push($("#selectItem5").val());
            itemList.push($("#selectItem6").val());
            console.log(itemList);
            console.log(itemList[0][0]);
            
            for(let i=0;i<itemList.length;i++)
            {
                if(itemList[i].length>0)
                {
                    for(let j=0;j<itemList[i].length;j++)
                    {
                        total+=parseInt($("#item"+itemList[i][j]).val())*parseInt($("#vol"+itemList[i][j]).val())*12;
                    }
                }
            }
            console.log("Total : ",total);
            $("#average_bill1").val(total);

        }
        function getSelectedValue(id){
            //alert("hello");
            var items = $("#selectItem"+id).val();
            var tableRow = "";
            for(let i = 0; i < items.length; i++){
                tableRow += "<tr><td><input type='text' name='"+items[i]+"' value='"+items[i]+"' readonly></td></td><td><input type='text' id='item"+items[i]+"' name='quantity[]' value='1'></td><td><input type='text' id='vol"+items[i]+"' name='voltage[]' value=''></td></tr>";
            }
            document.getElementById("selectedItem"+id).innerHTML=tableRow;
            //console.log($("#selectItem").val());
        }
    </script>
    <script>
        function equipment(val, name){
            // alert(val);
            console.log(name[0].id);

            var name = name[0].id;
                if(name == 'inverter' ){
                    if(val == 'Others'){
                        document.getElementById('inverterOther_input').style.display = "block";
                        document.getElementById('inverterOther').value = "";
                    }else{
                        document.getElementById('inverterOther_input').style.display = "none";
                    }
                }else if(name == 'monitor'){
                    if(val == 'Others'){
                        document.getElementById('monitorOther_input').style.display = "block";
                        document.getElementById('monitorOther').value = "";
                    }else{
                        document.getElementById('monitorOther_input').style.display = "none";
                    }
                }else if(name == 'racking'){
                    if(val == 'Others'){
                        document.getElementById('rackingOther_input').style.display = "block";
                        document.getElementById('rackingOther').value = "";
                    }else{
                        document.getElementById('rackingOther_input').style.display = "none";
                    }
                }else if(name == 'module'){
                    if(val == 'Others'){
                        document.getElementById('moduleOther_input').style.display = "block";
                        document.getElementById('moduleOther').value = "";
                    }else{
                        document.getElementById('moduleOther_input').style.display = "none";
                }
            }
        }
        function equipment1(val, name){
            // alert(val);
            console.log(name[1].id);

            var name = name[1].id;
            if(name == 'inverter1'){
                    if(val == 'Others'){
                        document.getElementById('inverterOther_input1').style.display = "block";
                        document.getElementById('inverterOther1').value = "";
                    }else{
                        document.getElementById('inverterOther_input1').style.display = "none";
                    }
                }else if(name == 'monitor1'){
                    if(val == 'Others'){
                        document.getElementById('monitorOther_input1').style.display = "block";
                        document.getElementById('monitorOther1').value = "";
                    }else{
                        document.getElementById('monitorOther_input1').style.display = "none";
                    }
                }else if(name == 'racking1'){
                    if(val == 'Others'){
                        document.getElementById('rackingOther_input1').style.display = "block";
                        document.getElementById('rackingOther1').value = "";
                    }else{
                        document.getElementById('rackingOther_input1').style.display = "none";
                    }
                }else if(name == 'module1'){
                    if(val == 'Others'){
                        document.getElementById('moduleOther_input1').style.display = "block";
                        document.getElementById('moduleOther1').value = "";
                    }else{
                        document.getElementById('moduleOther_input1').style.display = "none";
                }
            }
        }
        function equipment2(val, name){
            // alert(val);
            console.log(name[2].id);

            var name = name[2].id;
            if(name == 'inverter2'){
                    if(val == 'Others'){
                        document.getElementById('inverterOther_input2').style.display = "block";
                        document.getElementById('inverterOther2').value = "";
                    }else{
                        document.getElementById('inverterOther_input2').style.display = "none";
                    }
                }else if(name == 'monitor2'){
                    if(val == 'Others'){
                        document.getElementById('monitorOther_input2').style.display = "block";
                        document.getElementById('monitorOther2').value = "";
                    }else{
                        document.getElementById('monitorOther_input2').style.display = "none";
                    }
                }else if(name == 'racking2'){
                    if(val == 'Others'){
                        document.getElementById('rackingOther_input2').style.display = "block";
                        document.getElementById('rackingOther2').value = "";
                    }else{
                        document.getElementById('rackingOther_input2').style.display = "none";
                    }
                }else if(name == 'module2'){
                    if(val == 'Others'){
                        document.getElementById('moduleOther_input2').style.display = "block";
                        document.getElementById('moduleOther2').value = "";
                    }else{
                        document.getElementById('moduleOther_input2').style.display = "none";
                }
            }
        }
    </script>
    <script src="{{asset('uppy/uppy.min.js')}}"></script>
    <script src="{{asset('js/validate/validate.min.js')}}"></script>
    <script src="https://js.stripe.com/v3/"></script>
    <script src="{{asset('js/designs/payment.js')}}"></script>

    <script type="text/javascript">
        const company = '{{(Auth::user()->company)?Auth::user()->company:"no-company"}}';
        let uppy1 = null;
        let uppy2 = null;
        let uppy3 = null;
        let uppy4 = null;
        let uppys1=null;
        let uppyb2=null;
        let fileCount = 0;
        let filesUploaded = 0;

        // Payment stuff
        const paymentHoldUrl = "{{route('payment.hold')}}";
        const stripePublicKey = "{{env('STRIPE_KEY')}}";


        document.addEventListener("DOMContentLoaded", function () {
            uppy1 = Uppy.Core({
                id: "files",
                debug: true,
                meta: {
                    save_as: ''
                },
                restrictions: {
                    maxFileSize: 21000000,
                    maxNumberOfFiles: 20
                },
                onBeforeUpload: (files) => {
                    const updatedFiles = {}
                    Object.keys(files).forEach(fileID => {
                        updatedFiles[fileID] = files[fileID];
                        updatedFiles[fileID].meta.name = Date.now() + '_' + files[fileID].name;
                    })
                    return updatedFiles
                }
            }).use(Uppy.Dashboard, {
                target: `#uppyAurora`,
                inline: true,
                hideUploadButton: true,
                note: "Upto 20 files of 20 MB each"
            }).use(Uppy.XHRUpload, {
                endpoint: "{{ env('SUN_STORAGE') }}/file",
                headers: {
                    'api-key': "{{env('SUN_STORAGE_KEY')}}"
                },
                fieldName: "file"
            });
            uppy1.on('upload-success', sendFileToDb);

            uppy1.on('file-added', (file) => {
                fileCount++;
            });

            uppy1.on('file-removed', (file) => {
                fileCount--;
            });
        });

        document.addEventListener("DOMContentLoaded", function () {
            uppy2 = Uppy.Core({
                id: "files",
                debug: true,
                meta: {
                    save_as: ''
                },
                restrictions: {
                    maxFileSize: 21000000,
                    maxNumberOfFiles: 20
                },
                onBeforeUpload: (files) => {
                    const updatedFiles = {}
                    Object.keys(files).forEach(fileID => {
                        updatedFiles[fileID] = files[fileID];
                        updatedFiles[fileID].meta.name = Date.now() + '_' + files[fileID].name;
                    })
                    return updatedFiles
                }
            }).use(Uppy.Dashboard, {
                target: `#uppyStructural`,
                inline: true,
                hideUploadButton: true,
                note: "Upto 20 files of 20 MB each"
            }).use(Uppy.XHRUpload, {
                endpoint: "{{ env('SUN_STORAGE') }}/file",
                headers: {
                    'api-key': "{{env('SUN_STORAGE_KEY')}}"
                },
                fieldName: "file"
            });
            uppy2.on('upload-success', sendFileToDb);

            uppy2.on('file-added', (file) => {
                fileCount++;
            });

            uppy2.on('file-removed', (file) => {
                fileCount--;
            });
        });


        document.addEventListener("DOMContentLoaded", function () {
            uppyb2 = Uppy.Core({
                id: "files",
                debug: true,
                meta: {
                    save_as: ''
                },
                restrictions: {
                    maxFileSize: 21000000,
                    maxNumberOfFiles: 20
                },
                onBeforeUpload: (files) => {
                    const updatedFiles = {}
                    Object.keys(files).forEach(fileID => {
                        updatedFiles[fileID] = files[fileID];
                        updatedFiles[fileID].meta.name = Date.now() + '_' + files[fileID].name;
                    })
                    return updatedFiles
                }
            }).use(Uppy.Dashboard, {
                target: `#uppyBill`,
                inline: true,
                hideUploadButton: true,
                note: "Upto 20 files of 20 MB each"
            }).use(Uppy.XHRUpload, {
                endpoint: "{{ env('SUN_STORAGE') }}/file",
                headers: {
                    'api-key': "{{env('SUN_STORAGE_KEY')}}"
                },
                fieldName: "file"
            });
            uppyb2.on('upload-success', sendFileToDb);

            uppyb2.on('file-added', (file) => {
                fileCount++;
            });

            uppyb2.on('file-removed', (file) => {
                fileCount--;
            });
        });
        document.addEventListener("DOMContentLoaded", function () {
            uppy3 = Uppy.Core({
                id: "files",
                debug: true,
                meta: {
                    save_as: ''
                },
                restrictions: {
                    maxFileSize: 21000000,
                    maxNumberOfFiles: 20
                },
                onBeforeUpload: (files) => {
                    const updatedFiles = {}
                    Object.keys(files).forEach(fileID => {
                        updatedFiles[fileID] = files[fileID];
                        updatedFiles[fileID].meta.name = Date.now() + '_' + files[fileID].name;
                    })
                    return updatedFiles
                }
            }).use(Uppy.Dashboard, {
                target: `#uppystructural_letter`,
                inline: true,
                hideUploadButton: true,
                note: "Upto 20 files of 20 MB each"
            }).use(Uppy.XHRUpload, {
                endpoint: "{{ env('SUN_STORAGE') }}/file",
                headers: {
                    'api-key': "{{env('SUN_STORAGE_KEY')}}"
                },
                fieldName: "file"
            });
            uppy3.on('upload-success', sendFileToDb);

            uppy3.on('file-added', (file) => {
                fileCount++;
            });

            uppy3.on('file-removed', (file) => {
                fileCount--;
            });
        });

        document.addEventListener("DOMContentLoaded", function () {
            uppys1 = Uppy.Core({
                id: "files",
                debug: true,
                meta: {
                    save_as: ''
                },
                restrictions: {
                    maxFileSize: 21000000,
                    maxNumberOfFiles: 20
                },
                onBeforeUpload: (files) => {
                    const updatedFiles = {}
                    Object.keys(files).forEach(fileID => {
                        updatedFiles[fileID] = files[fileID];
                        updatedFiles[fileID].meta.name = Date.now() + '_' + files[fileID].name;
                    })
                    return updatedFiles
                }
            }).use(Uppy.Dashboard, {
                target: `#uppySupportingDocuments1`,
                inline: true,
                hideUploadButton: true,
                note: "Upto 20 files of 20 MB each"
            }).use(Uppy.XHRUpload, {
                endpoint: "{{ env('SUN_STORAGE') }}/file",
                headers: {
                    'api-key': "{{env('SUN_STORAGE_KEY')}}"
                },
                fieldName: "file"
            });
            uppys1.on('upload-success', sendFileToDb);

            uppys1.on('file-added', (file) => {
                fileCount++;
            });

            uppys1.on('file-removed', (file) => {
                fileCount--;
            });
        });

        document.addEventListener("DOMContentLoaded", function () {
            uppy4 = Uppy.Core({
                id: "files",
                debug: true,
                meta: {
                    save_as: ''
                },
                restrictions: {
                    maxFileSize: 21000000,
                    maxNumberOfFiles: 20
                },
                onBeforeUpload: (files) => {
                    const updatedFiles = {}
                    Object.keys(files).forEach(fileID => {
                        updatedFiles[fileID] = files[fileID];
                        updatedFiles[fileID].meta.name = Date.now() + '_' + files[fileID].name;
                    })
                    return updatedFiles
                }
            }).use(Uppy.Dashboard, {
                target: `#uppyplanset`,
                inline: true,
                hideUploadButton: true,
                note: "Upto 20 files of 20 MB each"
            }).use(Uppy.XHRUpload, {
                endpoint: "{{ env('SUN_STORAGE') }}/file",
                headers: {
                    'api-key': "{{env('SUN_STORAGE_KEY')}}"
                },
                fieldName: "file"
            });
            uppy4.on('upload-success', sendFileToDb);

            uppy4.on('file-added', (file) => {
                fileCount++;
            });

            uppy4.on('file-removed', (file) => {
                fileCount--;
            });
        });

        const sendFileToDb = function (file, response) {

        fetch("{{route('design.file.attach')}}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector("meta[name='csrf-token']").getAttribute('content')
            },
            body: JSON.stringify({
                path: response.body.name,
                system_design_id: file.meta.system_design_id,
                content_type: file.meta.type
            })
        }).then(async response => {
            return {db_response: await response.json(), "status": response.status};
        }).then(response => {
            if (response.status === 200 || response.status === 201) {
                console.log(response.db_response);
                toastr.success('Images Uploaded', '', { positionClass: 'toast-top-right', containerId: 'toast-top-right' });
                // M.toast({
                //     html: "Images uploaded",
                //     classes: "green"
                // });
                filesUploaded++;
                if (filesUploaded === fileCount)
                    window.location = "{{route('design.list', $project_id)}}";
            } else {
                toastr.error('There was a error uploading images. Please try again!', '', { positionClass: 'toast-top-right', containerId: 'toast-top-right' });
                console.error(response);
            }
        }).catch(err => {
            toastr.error('There was a network error uploading images. Please try again!', '', { positionClass: 'toast-top-right', containerId: 'toast-top-right' });
            console.error(err);
        });

        };
    </script>

    <script>
        var count=0;
        function insert(elem){
           count++;
        if(count==1)
        {
            const validationResult = validateFields();

            console.log("data", validationResult);
            //alert(validationResult);
            
            //alert(fields.arrays.length);
            document.getElementById('stripe_card').style.display = 'none'

            function uploadFiles(designs,system_design_id) {
                if(designs=='aurora')
                {
                uppy1.setMeta({system_design_id: system_design_id, path: `genesis/${company}/design_requests/${system_design_id}`})
                uppy1.upload();
                }
                else  if(designs=='structural'){
                uppy2.setMeta({system_design_id: system_design_id, path: `genesis/${company}/design_requests/${system_design_id}`})
                uppy2.upload();
                }
                else if(designs=='permit')
                {
                uppys1.setMeta({system_design_id: system_design_id, path: `genesis/${company}/design_requests/${system_design_id}`})
                uppys1.upload();
                uppyb2.setMeta({system_design_id: system_design_id, path: `genesis/${company}/design_requests/${system_design_id}`})
                uppyb2.upload();
                }
                else 
                {
                uppy3.setMeta({system_design_id: system_design_id, path: `genesis/${company}/design_requests/${system_design_id}`})
                uppy3.upload();
                uppy4.setMeta({system_design_id: system_design_id, path: `genesis/${company}/design_requests/${system_design_id}`})
                uppy4.upload();
                }
            }
            if (validationResult.errors === 0) {
                @foreach($type as $t)
                holdPayment('{{$t}}').then(resp=>{
                    console.log("{{$t}} : ",resp);
                    if (resp) {
                        if (resp.error) {
                            document.getElementById('stripe_error').innerText = resp.error.message;
                            elem.disabled = false;
                            document.getElementById('stripe_card').style.display = 'block';
                            toastr.error('Make your Payment Method Default in Profile!', '', { positionClass: 'toast-top-right', containerId: 'toast-top-right' });
                        } else {
                    @if($t=="aurora design")
                    validationResult.columns['stripe_payment_aurora'] = resp.paymentIntent.id;
                    @elseif($t=="structural load letter and calculations")
                    validationResult.columns['stripe_payment_structural'] = resp.paymentIntent.id;
                    @elseif($t=="PE stamping")
                    validationResult.columns['stripe_payment_stamping'] = resp.paymentIntent.id;
                    @elseif($t=="engineering permit package")
                    validationResult.columns['stripe_payment_permit'] = resp.paymentIntent.id;
                    @else
                        validationResult.columns['stripe_payment_electrical'] = resp.paymentIntent.id;
                    @endif
            
                    @if(end($type)==$t)
                    setTimeout(function(){
                    console.log(validationResult.columns);
                        fetch("{{ route('design.multiple_design') }}", {
                                method: 'post',
                                body: JSON.stringify(validationResult.columns),
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector("meta[name='csrf-token']").getAttribute('content')
                                }
                            }).then(async response => {
                                return {db_response: await response.json(), "status": response.status};
                            }).then(response => {
                                if (response.status === 200 || response.status === 201) {
                                    console.log(response.db_response);
                                    for(i=0;i<response.db_response.length;i++)
                                    uploadFiles(response.db_response[i]['name'],response.db_response[i]['design_id']);
                                    if (fileCount === 0)
                                        // window.location = "{{route('design.list', $project_id)}}";
                                        toastr.success('Design Submitted', '', { positionClass: 'toast-top-right', containerId: 'toast-top-right' });
                                } else {
                                    toastr.error('There was a error inserting the design. Please try again!', '', { positionClass: 'toast-top-right', containerId: 'toast-top-right' });
                                    console.error(response);
                                    elem.disabled = false;
                                }
                            }).catch(err => {
                                toastr.error('There was a network error. Please try again!', '', { positionClass: 'toast-top-right', containerId: 'toast-top-right' });
                                console.error(err);
                                elem.disabled = false;
                            });},5000);
                    @endif
                    }}else {
                        console.log("error")
                        elem.disabled = false;
                    }
                })
                @endforeach
            } else {
                toastr.error('There are some errors in your form, please fix them and try again!', '', { positionClass: 'toast-top-right', containerId: 'toast-top-right' });
                elem.disabled = false;
            }
        }
        else
        {
            toastr.success('Submission In Process Please Wait!', '', { positionClass: 'toast-top-right', containerId: 'toast-top-right' });
            if(count>5)
            {
                toastr.info('Form Failed To Submit  Try Again!', '', { positionClass: 'toast-top-right', containerId: 'toast-top-right' });
             
            }
        }
        }
    </script>
@endsection