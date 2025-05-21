<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Departamento;
use App\Models\Ciudad;
use App\Models\CodigoPostal;

class PeruLocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Array con los datos de Perú
        $data = [
            'Amazonas' => [
                'Chachapoyas' => '01001',
                'Bagua' => '01201',
                'Bongará' => '01301',
                'Condorcanqui' => '01401',
                'Luya' => '01501',
                'Rodríguez de Mendoza' => '01601',
                'Utcubamba' => '01701'
            ],
            'Áncash' => [
                'Huaraz' => '02001',
                'Aija' => '02101',
                'Antonio Raymondi' => '02201',
                'Asunción' => '02301',
                'Bolognesi' => '02401',
                'Carhuaz' => '02501',
                'Casma' => '02601',
                'Corongo' => '02701',
                'Huari' => '02801',
                'Huarmey' => '02901',
                'Huaylas' => '03001',
                'Mariscal Luzuriaga' => '03101',
                'Ocros' => '03201',
                'Pallasca' => '03301',
                'Pomabamba' => '03401',
                'Recuay' => '03501',
                'Santa' => '03601',
                'Sihuas' => '03701',
                'Yungay' => '03801'
            ],
            'Apurímac' => [
                'Abancay' => '03901',
                'Andahuaylas' => '03902',
                'Antabamba' => '03903',
                'Aymaraes' => '03904',
                'Cotabambas' => '03905',
                'Chincheros' => '03906',
                'Grau' => '03907'
            ],
            'Arequipa' => [
                'Arequipa' => '04001',
                'Camaná' => '04101',
                'Caravelí' => '04201',
                'Castilla' => '04301',
                'Caylloma' => '04401',
                'Condesuyos' => '04501',
                'Islay' => '04601',
                'La Unión' => '04701',
                'Yanahuara' => '04002',
                'Cayma' => '04003',
                'Cerro Colorado' => '04004',
                'Paucarpata' => '04005',
                'José Luis Bustamante y Rivero' => '04006',
                'Alto Selva Alegre' => '04007',
                'Miraflores' => '04008',
                'Mariano Melgar' => '04009'
            ],
            'Ayacucho' => [
                'Huamanga' => '05001',
                'Cangallo' => '05101',
                'Huanca Sancos' => '05201',
                'Huanta' => '05301',
                'La Mar' => '05401',
                'Lucanas' => '05501',
                'Parinacochas' => '05601',
                'Páucar del Sara Sara' => '05701',
                'Sucre' => '05801',
                'Víctor Fajardo' => '05901',
                'Vilcas Huamán' => '06001'
            ],
            'Cajamarca' => [
                'Cajamarca' => '06101',
                'Cajabamba' => '06201',
                'Celendín' => '06301',
                'Chota' => '06401',
                'Contumazá' => '06501',
                'Cutervo' => '06601',
                'Hualgayoc' => '06701',
                'Jaén' => '06801',
                'San Ignacio' => '06901',
                'San Marcos' => '07001',
                'San Miguel' => '07101',
                'San Pablo' => '07201',
                'Santa Cruz' => '07301'
            ],
            'Callao' => [
                'Callao' => '07401',
                'Bellavista' => '07402',
                'Carmen de La Legua' => '07403',
                'La Perla' => '07404',
                'La Punta' => '07405',
                'Ventanilla' => '07406',
                'Mi Perú' => '07407'
            ],
            'Cusco' => [
                'Cusco' => '08001',
                'Acomayo' => '08101',
                'Anta' => '08201',
                'Calca' => '08301',
                'Canas' => '08401',
                'Canchis' => '08501',
                'Chumbivilcas' => '08601',
                'Espinar' => '08701',
                'La Convención' => '08801',
                'Paruro' => '08901',
                'Paucartambo' => '09001',
                'Quispicanchi' => '09101',
                'Urubamba' => '09201',
                'Wanchaq' => '08002',
                'San Sebastián' => '08003',
                'San Jerónimo' => '08004'
            ],
            'Huancavelica' => [
                'Huancavelica' => '09301',
                'Acobamba' => '09401',
                'Angaraes' => '09501',
                'Castrovirreyna' => '09601',
                'Churcampa' => '09701',
                'Huaytará' => '09801',
                'Tayacaja' => '09901'
            ],
            'Huánuco' => [
                'Huánuco' => '10001',
                'Ambo' => '10101',
                'Dos de Mayo' => '10201',
                'Huacaybamba' => '10301',
                'Huamalíes' => '10401',
                'Leoncio Prado' => '10501',
                'Marañón' => '10601',
                'Pachitea' => '10701',
                'Puerto Inca' => '10801',
                'Lauricocha' => '10901',
                'Yarowilca' => '11001',
                'Pillco Marca' => '10002',
                'Amarilis' => '10003'
            ],
            'Ica' => [
                'Ica' => '11101',
                'Chincha' => '11201',
                'Nazca' => '11301',
                'Palpa' => '11401',
                'Pisco' => '11501',
                'Parcona' => '11102',
                'Subtanjalla' => '11103',
                'La Tinguiña' => '11104',
                'Los Aquijes' => '11105'
            ],
            'Junín' => [
                'Huancayo' => '12001',
                'Concepción' => '12101',
                'Chanchamayo' => '12201',
                'Jauja' => '12301',
                'Junín' => '12401',
                'Satipo' => '12501',
                'Tarma' => '12601',
                'Yauli' => '12701',
                'Chupaca' => '12801',
                'El Tambo' => '12002',
                'Chilca' => '12003',
                'Pilcomayo' => '12004',
                'San Agustín' => '12005'
            ],
            'La Libertad' => [
                'Trujillo' => '13001',
                'Ascope' => '13101',
                'Bolívar' => '13201',
                'Chepén' => '13301',
                'Julcán' => '13401',
                'Otuzco' => '13501',
                'Pacasmayo' => '13601',
                'Pataz' => '13701',
                'Sánchez Carrión' => '13801',
                'Santiago de Chuco' => '13901',
                'Gran Chimú' => '14901',
                'Virú' => '14911',
                'La Esperanza' => '13002',
                'El Porvenir' => '13003',
                'Florencia de Mora' => '13004',
                'Víctor Larco Herrera' => '13005',
                'Huanchaco' => '13006'
            ],
            'Lambayeque' => [
                'Chiclayo' => '14001',
                'Ferreñafe' => '14101',
                'Lambayeque' => '14201',
                'José Leonardo Ortiz' => '14010',
                'La Victoria' => '14011',
                'Pimentel' => '14012',
                'Monsefú' => '14013',
                'Pomalca' => '14014',
                'Tuman' => '14015',
                'Pátapo' => '14016',
                'Oyotún' => '14017'
            ],
            'Lima' => [
                'Lima' => '15001',
                'Barranca' => '15101',
                'Cajatambo' => '15201',
                'Canta' => '15301',
                'Cañete' => '15401',
                'Huaral' => '15501',
                'Huarochirí' => '15601',
                'Huaura' => '15701',
                'Oyón' => '15801',
                'Yauyos' => '15901',
                'San Juan de Lurigancho' => '15002',
                'San Martín de Porres' => '15003',
                'Ate' => '15004',
                'Miraflores' => '15005',
                'San Isidro' => '15006',
                'La Molina' => '15007',
                'Surco' => '15008',
                'Los Olivos' => '15009',
                'San Juan de Miraflores' => '15010',
                'Villa El Salvador' => '15011',
                'Villa María del Triunfo' => '15012',
                'Chorrillos' => '15013',
                'Rímac' => '15014',
                'Comas' => '15015',
                'Carabayllo' => '15016',
                'Independencia' => '15017',
                'Puente Piedra' => '15018',
                'Barranco' => '15019',
                'Jesús María' => '15020',
                'Pueblo Libre' => '15021',
                'San Miguel' => '15022',
                'Magdalena del Mar' => '15023',
                'Lince' => '15024',
                'San Luis' => '15025',
                'San Borja' => '15026',
                'Surquillo' => '15027',
                'Breña' => '15028',
                'La Victoria' => '15029'
            ],
            'Loreto' => [
                'Maynas' => '16001',
                'Alto Amazonas' => '16101',
                'Loreto' => '16201',
                'Mariscal Ramón Castilla' => '16301',
                'Requena' => '16401',
                'Ucayali' => '16501',
                'Putumayo' => '16601',
                'Datem del Marañón' => '16701',
                'Iquitos' => '16002',
                'Belén' => '16003',
                'Punchana' => '16004',
                'San Juan Bautista' => '16005'
            ],
            'Madre de Dios' => [
                'Tambopata' => '17001',
                'Manu' => '17101',
                'Tahuamanu' => '17201',
                'Puerto Maldonado' => '17002',
                'Inambari' => '17003',
                'Laberinto' => '17004',
                'Iñapari' => '17005'
            ],
            'Moquegua' => [
                'Mariscal Nieto' => '18001',
                'General Sánchez Cerro' => '18101',
                'Ilo' => '18201',
                'Moquegua' => '18002',
                'Samegua' => '18003',
                'Torata' => '18004'
            ],
            'Pasco' => [
                'Pasco' => '19001',
                'Daniel Alcides Carrión' => '19101',
                'Oxapampa' => '19201',
                'Chaupimarca' => '19002',
                'Yanacancha' => '19003',
                'Simón Bolívar' => '19004',
                'Villa Rica' => '19005'
            ],
            'Piura' => [
                'Piura' => '20001',
                'Ayabaca' => '20101',
                'Huancabamba' => '20201',
                'Morropón' => '20301',
                'Paita' => '20401',
                'Sullana' => '20501',
                'Talara' => '20601',
                'Sechura' => '20701',
                'Castilla' => '20002',
                'Catacaos' => '20003',
                'Tambogrande' => '20004',
                'Veintiséis de Octubre' => '20005'
            ],
            'Puno' => [
                'Puno' => '21001',
                'Azángaro' => '21101',
                'Carabaya' => '21201',
                'Chucuito' => '21301',
                'El Collao' => '21401',
                'Huancané' => '21501',
                'Lampa' => '21601',
                'Melgar' => '21701',
                'Moho' => '21801',
                'San Antonio de Putina' => '21901',
                'San Román' => '22001',
                'Sandia' => '22101',
                'Yunguyo' => '22201',
                'Juliaca' => '22002',
                'Ilave' => '22003',
                'Ayaviri' => '22004'
            ],
            'San Martín' => [
                'Moyobamba' => '22301',
                'Bellavista' => '22401',
                'El Dorado' => '22501',
                'Huallaga' => '22601',
                'Lamas' => '22701',
                'Mariscal Cáceres' => '22801',
                'Picota' => '22901',
                'Rioja' => '23001',
                'San Martín' => '23101',
                'Tocache' => '23201',
                'Tarapoto' => '23102',
                'Nueva Cajamarca' => '23003',
                'Juanjuí' => '23004'
            ],
            'Tacna' => [
                'Tacna' => '23301',
                'Candarave' => '23401',
                'Jorge Basadre' => '23501',
                'Tarata' => '23601',
                'Alto de la Alianza' => '23302',
                'Ciudad Nueva' => '23303',
                'Pocollay' => '23304',
                'Coronel Gregorio Albarracín' => '23305'
            ],
            'Tumbes' => [
                'Tumbes' => '24001',
                'Contralmirante Villar' => '24101',
                'Zarumilla' => '24201',
                'Corrales' => '24002',
                'La Cruz' => '24003',
                'San Jacinto' => '24004',
                'Pampas de Hospital' => '24005',
                'San Juan de la Virgen' => '24006',
                'Aguas Verdes' => '24202'
            ],
            'Ucayali' => [
                'Coronel Portillo' => '25001',
                'Atalaya' => '25101',
                'Padre Abad' => '25201',
                'Purús' => '25301',
                'Callería' => '25002',
                'Yarinacocha' => '25003',
                'Manantay' => '25004',
                'Campo Verde' => '25005',
                'Nueva Requena' => '25006'
            ]
        ];

        // Insertar departamentos y sus ciudades
        foreach ($data as $departamentoNombre => $ciudades) {
            $departamento = Departamento::firstOrCreate(['nombre' => $departamentoNombre]);

            foreach ($ciudades as $ciudadNombre => $codigoPostal) {
                $ciudad = Ciudad::firstOrCreate([
                    'nombre' => $ciudadNombre,
                    'departamento_id' => $departamento->id
                ]);

                CodigoPostal::firstOrCreate([
                    'codigo' => $codigoPostal,
                    'ciudad_id' => $ciudad->id
                ]);
            }
        }

    }
}