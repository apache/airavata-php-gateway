@extends('layout.basic')
{{ HTML::script('js/datacat/jquery-1.7.min.js')}}
{{ HTML::script('js/datacat/Three.js')}}
{{ HTML::script('js/datacat/GLmol.js')}}

@section('page-header')
    @parent
@stop
@section('content')
    <div style="margin-left: 5%; margin-right: 5%; margin-top: 5px; margin-bottom: 5px">
    @if ( isset($result))
            <h1>Molecule Summary</h1>
            </br>
            <table class="table table-bordered">
                <tr>
                    <td><strong>FinalGeom</strong></td>
                    <td>
                        <div id="mol" style="width: 200px; height: 200px; background-color: black;"></div>
                        @if(isset($result['PDB']))
                            <textarea id="mol_src" style="display: none;">{{$result['PDB']}}</textarea>
                            <script type="text/javascript">
                                $( document ).ready(function() {
                                    var mol  = new GLmol('mol', true);

                                    mol.defineRepresentation = function () {
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

                                    mol.loadMolecule();
                                });
                            </script>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td><strong>InChI</strong></td>
                    <td>@if(isset($result['InChI'])){{$result['InChI']}}@endif</td>
                </tr>
                <tr>
                    <td><strong>InChIKey</strong></td>
                    <td>@if(isset($result['InChIKey'])){{$result['InChIKey']}}@endif</td>
                </tr>
                <tr>
                    <td><strong>SMILES</strong></td>
                    <td>@if(isset($result['SMILES'])){{$result['SMILES']}}@endif</td>
                </tr>
                <tr>
                    <td><strong>CanonicalSMILES</strong></td>
                    <td>@if(isset($result['CanonicalSMILES'])){{$result['CanonicalSMILES']}}@endif</td>
                </tr>
                <tr>
                    <td><strong>Formula</strong></td>
                    <td>@if(isset($result['Formula']))<a href="#">{{$result['Formula']}}</a>@endif</td>
                </tr>
            </table>

            </br>
            <h3>Detailed Information</h3>
            <table class="table table-bordered">
                <tr>
                    <td><strong>ParsedBy</strong></td>
                    <td>@if(isset($result['ParsedBy'])){{$result['ParsedBy']}}@endif</td>
                </tr>
                <tr>
                    <td><strong>Charge</strong></td>
                    <td>@if(isset($result['Charge'])){{$result['Charge']}}@endif</td>
                </tr>
                <tr>
                    <td><strong>Multiplicity</strong></td>
                    <td>@if(isset($result['Multiplicity'])){{$result['Multiplicity']}}@endif</td>
                </tr>
                <tr>
                    <td><strong>Keywords</strong></td>
                    <td>@if(isset($result['Keywords'])){{$result['Keywords']}}@endif</td>
                </tr>
                <tr>
                    <td><strong>CalcType</strong></td>
                    <td>@if(isset($result['CalcType'])){{$result['CalcType']}}@endif</td>
                </tr>
                <tr>
                    <td><strong>Methods</strong></td>
                    <td>@if(isset($result['Methods'])){{$result['Methods']}}@endif</td>
                </tr>
                <tr>
                    <td><strong>Basis</strong></td>
                    <td>@if(isset($result['Basis'])){{$result['Basis']}}@endif</td>
                </tr>
                <tr>
                    <td><strong>NumBasis</strong></td>
                    <td>@if(isset($result['NumBasis'])){{$result['NumBasis']}}@endif</td>
                </tr>
                <tr>
                    <td><strong>NumFC</strong></td>
                    <td>@if(isset($result['NumFC'])){{$result['NumFC']}}@endif</td>
                </tr>
                <tr>
                    <td><strong>NumVirt</strong></td>
                    <td>@if(isset($result['NumVirt'])){{$result['NumVirt']}}@endif</td>
                </tr>
                <tr>
                    <td><strong>JobStatus</strong></td>
                    <td>@if(isset($result['JobStatus'])){{$result['JobStatus']}}@endif</td>
                </tr>
                <tr>
                    <td><strong>FinTime</strong></td>
                    <td>@if(isset($result['FinTime'])){{$result['FinTime']}}@endif</td>
                </tr>
                <tr>
                    <td><strong>InitGeom</strong></td>
                    <td>@if(isset($result['InitGeom'])){{$result['InitGeom']}}@endif</td>
                </tr>
                <tr>
                    <td><strong>FinalGeom</strong></td>
                    <td>@if(isset($result['FinalGeom'])){{$result['FinalGeom']}}@endif</td>
                </tr>
                <tr>
                    <td><strong>PG</strong></td>
                    <td>@if(isset($result['PG'])){{$result['PG']}}@endif</td>
                </tr>
                <tr>
                    <td><strong>ElecSym</strong></td>
                    <td>@if(isset($result['ElecSym'])){{$result['ElecSym']}}@endif</td>
                </tr>
                <tr>
                    <td><strong>NImag</strong></td>
                    <td>@if(isset($result['NImag'])){{$result['NImag']}}@endif</td>
                </tr>
                <tr>
                    <td><strong>Energy</strong></td>
                    <td>@if(isset($result['Energy'])){{$result['Energy']}}@endif</td>
                </tr>
                <tr>
                    <td><strong>EnergyKcal</strong></td>
                    <td>@if(isset($result['EnergyKcal'])){{$result['EnergyKcal']}}@endif</td>
                </tr>
                <tr>
                    <td><strong>ZPE</strong></td>
                    <td>@if(isset($result['ZPE'])){{$result['ZPE']}}@endif</td>
                </tr>
                <tr>
                    <td><strong>ZPEKcal</strong></td>
                    <td>@if(isset($result['ZPEKcal'])){{$result['ZPEKcal']}}@endif</td>
                </tr>
                <tr>
                    <td><strong>HF</strong></td>
                    <td>@if(isset($result['HF'])){{$result['HF']}}@endif</td>
                </tr>
                <tr>
                    <td><strong>HFKcal</strong></td>
                    <td>@if(isset($result['HFKcal'])){{$result['HFKcal']}}@endif</td>
                </tr>
                <tr>
                    <td><strong>Thermal</strong></td>
                    <td>@if(isset($result['Thermal'])){{$result['Thermal']}}@endif</td>
                </tr>
                <tr>
                    <td><strong>ThermalKcal</strong></td>
                    <td>@if(isset($result['ThermalKcal'])){{$result['ThermalKcal']}}@endif</td>
                </tr>
                <tr>
                    <td><strong>Enthalpy</strong></td>
                    <td>@if(isset($result['Enthalpy'])){{$result['Enthalpy']}}@endif</td>
                </tr>
                <tr>
                    <td><strong>EnthalpyKcal</strong></td>
                    <td>@if(isset($result['EnthalpyKcal'])){{$result['EnthalpyKcal']}}@endif</td>
                </tr>
                <tr>
                    <td><strong>Entropy</strong></td>
                    <td>@if(isset($result['Entropy'])){{$result['Entropy']}}@endif</td>
                </tr>
                <tr>
                    <td><strong>EntropyKcal</strong></td>
                    <td>@if(isset($result['EntropyKcal'])){{$result['EntropyKcal']}}@endif</td>
                </tr>
                <tr>
                    <td><strong>Gibbs</strong></td>
                    <td>@if(isset($result['Gibbs'])){{$result['Gibbs']}}@endif</td>
                </tr>
                <tr>
                    <td><strong>GibbsKcal</strong></td>
                    <td>@if(isset($result['GibbsKcal'])){{$result['GibbsKcal']}}@endif</td>
                </tr>
                <tr>
                    <td><strong>OrbSym</strong></td>
                    <td>@if(isset($result['OrbSym'])){{$result['OrbSym']}}@endif</td>
                </tr>
                <tr>
                    <td><strong>Dipole</strong></td>
                    <td>@if(isset($result['Dipole'])){{$result['Dipole']}}@endif</td>
                </tr>
                <tr>
                    <td><strong>Freq</strong></td>
                    <td>@if(isset($result['Freq'])){{$result['Freq']}}@endif</td>
                </tr>
                <tr>
                    <td><strong>AtomWeigh</strong></td>
                    <td>@if(isset($result['AtomWeigh'])){{$result['AtomWeigh']}}@endif</td>
                </tr>
                <tr>
                    <td><strong>Conditions</strong></td>
                    <td>@if(isset($result['Conditions'])){{$result['Conditions']}}@endif</td>
                </tr>
                <tr>
                    <td><strong>ReacGeom</strong></td>
                    <td>@if(isset($result['ReacGeom'])){{$result['ReacGeom']}}@endif</td>
                </tr>
                <tr>
                    <td><strong>ProdGeom</strong></td>
                    <td>@if(isset($result['ProdGeom'])){{$result['ProdGeom']}}@endif</td>
                </tr>
                <tr>
                    <td><strong>MulCharge</strong></td>
                    <td>@if(isset($result['MulCharge'])){{$result['MulCharge']}}@endif</td>
                </tr>
                <tr>
                    <td><strong>NatCharge</strong></td>
                    <td>@if(isset($result['NatCharge'])){{$result['NatCharge']}}@endif</td>
                </tr>
                <tr>
                    <td><strong>S2</strong></td>
                    <td>@if(isset($result['S2'])){{$result['S2']}}@endif</td>
                </tr>
                <tr>
                    <td><strong>CodeVersion</strong></td>
                    <td>@if(isset($result['CodeVersion'])){{$result['CodeVersion']}}@endif</td>
                </tr>
                <tr>
                    <td><strong>CalcMachine</strong></td>
                    <td>@if(isset($result['CalcMachine'])){{$result['CalcMachine']}}@endif</td>
                </tr>
                <tr>
                    <td><strong>CalcBy</strong></td>
                    <td>@if(isset($result['CalcBy'])){{$result['CalcBy']}}@endif</td>
                </tr>
                <tr>
                    <td><strong>MemCost</strong></td>
                    <td>@if(isset($result['MemCost'])){{$result['MemCost']}}@endif</td>
                </tr>
                <tr>
                    <td><strong>TimeCost</strong></td>
                    <td>@if(isset($result['TimeCost'])){{$result['TimeCost']}}@endif</td>
                </tr>
                <tr>
                    <td><strong>CPUTime</strong></td>
                    <td>@if(isset($result['CPUTime'])){{$result['CPUTime']}}@endif</td>
                </tr>
                <tr>
                    <td><strong>Convergence</strong></td>
                    <td>@if(isset($result['Convergence'])){{$result['Convergence']}}@endif</td>
                </tr>
                <tr>
                    <td><strong>FullPath</strong></td>
                    <td>@if(isset($result['FullPath'])){{$result['FullPath']}}@endif</td>
                </tr>
                <tr>
                    <td><strong>InputButGeom</strong></td>
                    <td>@if(isset($result['InputButGeom'])){{$result['InputButGeom']}}@endif</td>
                </tr>
                <tr>
                    <td><strong>OtherInfo</strong></td>
                    <td>@if(isset($result['OtherInfo'])){{$result['OtherInfo']}}@endif</td>
                </tr>
                <tr>
                    <td><strong>Comments</strong></td>
                    <td>@if(isset($result['Comments'])){{$result['Comments']}}@endif</td>
                </tr>
                <tr>
                    <td><strong>NAtom</strong></td>
                    <td>@if(isset($result['NAtom'])){{$result['NAtom']}}@endif</td>
                </tr>
                <tr>
                    <td><strong>Nmo</strong></td>
                    <td>@if(isset($result['Nmo'])){{$result['Nmo']}}@endif</td>
                </tr>
                <tr>
                    <td><strong>NBasis</strong></td>
                    <td>@if(isset($result['NBasis'])){{$result['NBasis']}}@endif</td>
                </tr>
                <tr>
                    <td><strong>AtomNos</strong></td>
                    <td>@if(isset($result['AtomNos'])){{json_encode($result['AtomNos'], JSON_PRETTY_PRINT)}}@endif</td>
                </tr>
                <tr>
                    <td><strong>NAtom</strong></td>
                    <td>@if(isset($result['NAtom'])){{$result['NAtom']}}@endif</td>
                </tr>
                <tr>
                    <td><strong>NAtom</strong></td>
                    <td>@if(isset($result['NAtom'])){{$result['NAtom']}}@endif</td>
                </tr>
                <tr>
                    <td><strong>Homos</strong></td>
                    <td>@if(isset($result['Homos'])){{json_encode($result['Homos'])}}@endif</td>
                </tr>
                <tr>
                    <td><strong>ScfEnerfgies</strong></td>
                    <td>@if(isset($result['ScfEnergies'])){{json_encode($result['ScfEnergies'], JSON_PRETTY_PRINT)}}@endif</td>
                </tr>
                <tr>
                    <td><strong>CoreElectrons</strong></td>
                    <td>@if(isset($result['CoreElectrons'])){{json_encode($result['CoreElectrons'], JSON_PRETTY_PRINT)}}@endif</td>
                </tr>
                <tr>
                    <td><strong>MoEnergies</strong></td>
                    <td>@if(isset($result['MoEnergies'])){{json_encode($result['MoEnergies'], JSON_PRETTY_PRINT)}}@endif</td>
                </tr>
                <tr>
                    <td><strong>AtomCoords</strong></td>
                    <td>@if(isset($result['AtomCoords'])){{json_encode($result['AtomCoords'], JSON_PRETTY_PRINT)}}@endif</td>
                </tr>
                <tr>
                    <td><strong>ScfTargets</strong></td>
                    <td>@if(isset($result['ScfTargets'])){{json_encode($result['ScfTargets'], JSON_PRETTY_PRINT)}}@endif</td>
                </tr>
            </table>
    @endif
    </div>
@stop