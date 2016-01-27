@extends('layout.basic')
{{ HTML::script('js/datacat/jquery-1.7.min.js')}}
{{ HTML::script('js/datacat/Three.js')}}
{{ HTML::script('js/datacat/GLmol.js')}}

@section('page-header')
    @parent
@stop
@section('content')
    <div class="container" style="max-width: 80%;">
    @if ( isset($results))
        @if (sizeof($results) != 0)
            <div id="re" class="table-responsive">
                <table class="table">
                    <tr>
                        <th>FinalGeom</th>
                        <th>Formula</th>
                        <th>Energy</th>
                        <th>ZPE</th>
                        <th>CalcType</th>
                        <th>Methods</th>
                        <th>Basis</th>
                        <th>Enthalpy</th>
                        <th>Gibbs</th>
                        <th>NImag</th>
                        <th>CodeVersion</th>
                    </tr>
                    @foreach($results as $key=>$result)
                        @if(isset($result['Formula']))
                            <tr>
                                <td>
                                    <div id="mol_{{$key}}" style="width: 100px; height: 100px; background-color: black;"></div>
                                    @if(isset($result['PDB']))
                                        <textarea id="mol_{{$key}}_src" style="display: none;">{{$result['PDB']}}</textarea>
                                        <script type="text/javascript">
                                            $( document ).ready(function() {
                                                var mol_{{$key}}  = new GLmol('mol_{{$key}}', true);

                                                mol_{{$key}}.defineRepresentation = function () {
                                                    var all = this.getAllAtoms();
                                                    var hetatm = this.removeSolvents(this.getHetatms(all));
                                                    this.colorByAtom(all, {});
                                                    this.colorByChain(all);
                                                    var asu = new THREE.Object3D();

                                                    this.drawBondsAsStick(asu, hetatm, this.cylinderRadius, this.cylinderRadius);
                                                    this.drawBondsAsStick(asu, this.getResiduesById(this.getSidechains(this.getChain(all, ['A'])), [58, 87]),
                                                            this.cylinderRadius, this.cylinderRadius);
                                                    this.drawBondsAsStick(asu, this.getResiduesById(this.getSidechains(this.getChain(all, ['B'])), [63, 92]),
                                                            this.cylinderRadius, this.cylinderRadius);
                                                    this.drawCartoon(asu, all, this.curveWidth, this.thickness);

                                                    this.drawSymmetryMates2(this.modelGroup, asu, this.protein.biomtMatrices);
                                                    this.modelGroup.add(asu);
                                                };

                                                mol_{{$key}}.loadMolecule();
                                            });
                                        </script>
                                    @endif
                                </td>
                                <td>@if(isset($result['Formula']))<a href="summary" target="_blank">{{$result['Formula']}}</a>@endif</td>
                                <td>@if(isset($result['Energy'])){{$result['Energy']}}@endif</td>
                                <td>@if(isset($result['ZPE'])){{$result['ZPE']}}@endif</td>
                                <td>@if(isset($result['CalcType'])){{$result['CalcType']}}@endif</td>
                                <td>@if(isset($result['Methods'])){{$result['Methods']}}@endif</td>
                                <td>@if(isset($result['Basis'])){{$result['Basis']}}@endif</td>
                                <td>@if(isset($result['Enthalpy'])){{$result['Enthalpy']}}@endif</td>
                                <td>@if(isset($result['Gibbs'])){{$result['Gibbs']}}@endif</td>
                                <td>@if(isset($result['NImag'])){{$result['NImag']}}@endif</td>
                                <td>@if(isset($result['CodeVersion'])){{$result['CodeVersion']}}@endif</td>
                            </tr>
                        @endif
                    @endforeach
                </table>
            </div>
        @endif
    @endif
    </div>
@stop