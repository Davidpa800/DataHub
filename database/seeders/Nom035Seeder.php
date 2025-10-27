<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB; // <-- Necesario para inserciones masivas

// Importa los modelos que vamos a poblar
use App\Models\Cuestionario;
use App\Models\PreguntaTipo;
use App\Models\OpcionRespuesta;
use App\Models\Seccion;
use App\Models\Pregunta;


class Nom035Seeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // --- 1. Crear Tipos de Pregunta ---
        $tipoSINO = PreguntaTipo::create([
            'tipo' => 'si_no_nom035',
            'descripcion' => 'Respuesta binaria de Sí/No para Guía I'
        ]);

        $tipoLikert5 = PreguntaTipo::create([
            'tipo' => 'likert_5_opciones_nom035',
            'descripcion' => 'Respuesta tipo Likert (Siempre, Casi Siempre, ...) para Guías II y III'
        ]);

        // --- 2. Crear Opciones de Respuesta ---
        // Opciones para Sí/No
        OpcionRespuesta::insert([
            ['pregunta_tipo_id' => $tipoSINO->id, 'texto_opcion' => 'Sí', 'valor_numerico' => 1, 'orden' => 1],
            ['pregunta_tipo_id' => $tipoSINO->id, 'texto_opcion' => 'No', 'valor_numerico' => 0, 'orden' => 2],
        ]);

        // Opciones para Likert 5
        OpcionRespuesta::insert([
            ['pregunta_tipo_id' => $tipoLikert5->id, 'texto_opcion' => 'Siempre', 'valor_numerico' => 4, 'orden' => 1],
            ['pregunta_tipo_id' => $tipoLikert5->id, 'texto_opcion' => 'Casi siempre', 'valor_numerico' => 3, 'orden' => 2],
            ['pregunta_tipo_id' => $tipoLikert5->id, 'texto_opcion' => 'Algunas veces', 'valor_numerico' => 2, 'orden' => 3],
            ['pregunta_tipo_id' => $tipoLikert5->id, 'texto_opcion' => 'Casi nunca', 'valor_numerico' => 1, 'orden' => 4],
            ['pregunta_tipo_id' => $tipoLikert5->id, 'texto_opcion' => 'Nunca', 'valor_numerico' => 0, 'orden' => 5],
        ]);

        // --- 3. Crear Cuestionarios ---
        $guia1 = Cuestionario::create([
            'id' => 1, // Forzar ID para que coincida con SQL si se usó antes
            'nombre' => 'Guía de Referencia I - Acontecimientos Traumáticos Severos',
            'tipo' => 'NOM-035',
            'descripcion' => 'Identifica a trabajadores que sufrieron un evento traumático severo.'
        ]);
        $guia2 = Cuestionario::create([
            'id' => 2,
            'nombre' => 'Guía de Referencia II - Riesgo Psicosocial (Hasta 50)',
            'tipo' => 'NOM-035',
            'descripcion' => 'Identifica y analiza factores de riesgo psicosocial en centros de trabajo de hasta 50 empleados.'
        ]);
        $guia3 = Cuestionario::create([
            'id' => 3,
            'nombre' => 'Guía de Referencia III - Riesgo Psicosocial (Más de 50)',
            'tipo' => 'NOM-035',
            'descripcion' => 'Identifica y analiza factores de riesgo psicosocial en centros de trabajo de más de 50 empleados.'
        ]);


        // -------------------------------------
        // --- 4. POBLAR GUÍA DE REFERENCIA I ---
        // -------------------------------------

        // Secciones Guía I
        $seccion1_g1 = Seccion::create(['cuestionario_id' => $guia1->id, 'titulo' => 'Sección I: Acontecimiento traumático severo', 'descripcion' => '¿Ha presenciado o sufrido alguna vez, durante o con motivo del trabajo un acontecimiento como los siguientes:', 'orden' => 1]);
        $seccion2_g1 = Seccion::create(['cuestionario_id' => $guia1->id, 'titulo' => 'Sección II: Recuerdos persistentes sobre el acontecimiento', 'orden' => 2]);
        $seccion3_g1 = Seccion::create(['cuestionario_id' => $guia1->id, 'titulo' => 'Sección III: Esfuerzo por evitar circunstancias parecidas o asociadas al acontecimiento', 'orden' => 3]);
        $seccion4_g1 = Seccion::create(['cuestionario_id' => $guia1->id, 'titulo' => 'Sección IV: Afectación (mayor de un mes)', 'orden' => 4]);

        // Preguntas Guía I - Sección I
        Pregunta::insert([
            ['seccion_id' => $seccion1_g1->id, 'pregunta_tipo_id' => $tipoSINO->id, 'texto_pregunta' => 'Accidente que tenga como consecuencia la muerte, la pérdida de un miembro o una lesión grave?', 'orden' => 1],
            ['seccion_id' => $seccion1_g1->id, 'pregunta_tipo_id' => $tipoSINO->id, 'texto_pregunta' => 'Asaltos?', 'orden' => 2],
            ['seccion_id' => $seccion1_g1->id, 'pregunta_tipo_id' => $tipoSINO->id, 'texto_pregunta' => 'Actos violentos que derivaron en lesiones graves?', 'orden' => 3],
            ['seccion_id' => $seccion1_g1->id, 'pregunta_tipo_id' => $tipoSINO->id, 'texto_pregunta' => 'Secuestro?', 'orden' => 4],
            ['seccion_id' => $seccion1_g1->id, 'pregunta_tipo_id' => $tipoSINO->id, 'texto_pregunta' => 'Amenazas?', 'orden' => 5],
            ['seccion_id' => $seccion1_g1->id, 'pregunta_tipo_id' => $tipoSINO->id, 'texto_pregunta' => 'Cualquier otro que ponga en riesgo su vida o integridad física o la de sus compañeros?', 'orden' => 6],
        ]);

        // Preguntas Guía I - Sección II
        Pregunta::insert([
            ['seccion_id' => $seccion2_g1->id, 'pregunta_tipo_id' => $tipoSINO->id, 'texto_pregunta' => '¿Ha tenido recuerdos recurrentes sobre el acontecimiento que le provocan malestares?', 'orden' => 1],
            ['seccion_id' => $seccion2_g1->id, 'pregunta_tipo_id' => $tipoSINO->id, 'texto_pregunta' => '¿Ha tenido sueños de carácter recurrente sobre el acontecimiento, que le producen malestar?', 'orden' => 2],
        ]);

        // Preguntas Guía I - Sección III
        Pregunta::insert([
            ['seccion_id' => $seccion3_g1->id, 'pregunta_tipo_id' => $tipoSINO->id, 'texto_pregunta' => '¿Se ha esforzado por evitar todo tipo de sentimientos, conversaciones o situaciones que le puedan recordar el acontecimiento?', 'orden' => 1],
            ['seccion_id' => $seccion3_g1->id, 'pregunta_tipo_id' => $tipoSINO->id, 'texto_pregunta' => '¿Se ha esforzado por evitar todo tipo de actividades, lugares o personas que motivan recuerdos del acontecimiento?', 'orden' => 2],
            ['seccion_id' => $seccion3_g1->id, 'pregunta_tipo_id' => $tipoSINO->id, 'texto_pregunta' => '¿Ha tenido dificultad para recordar alguna parte importante del evento?', 'orden' => 3],
            ['seccion_id' => $seccion3_g1->id, 'pregunta_tipo_id' => $tipoSINO->id, 'texto_pregunta' => '¿Ha disminuido su interés en sus actividades cotidianas?', 'orden' => 4],
            ['seccion_id' => $seccion3_g1->id, 'pregunta_tipo_id' => $tipoSINO->id, 'texto_pregunta' => '¿Se ha sentido usted alejado o distante de los demás?', 'orden' => 5],
            ['seccion_id' => $seccion3_g1->id, 'pregunta_tipo_id' => $tipoSINO->id, 'texto_pregunta' => '¿Ha notado que tiene dificultad para expresar sus sentimientos?', 'orden' => 6],
            ['seccion_id' => $seccion3_g1->id, 'pregunta_tipo_id' => $tipoSINO->id, 'texto_pregunta' => '¿Ha tenido la impresión de que su futuro se acorta, que vivirá menos tiempo?', 'orden' => 7],
        ]);

        // Preguntas Guía I - Sección IV
        Pregunta::insert([
            ['seccion_id' => $seccion4_g1->id, 'pregunta_tipo_id' => $tipoSINO->id, 'texto_pregunta' => '¿Ha tenido usted dificultades para dormir?', 'orden' => 1],
            ['seccion_id' => $seccion4_g1->id, 'pregunta_tipo_id' => $tipoSINO->id, 'texto_pregunta' => '¿Ha estado particularmente irritable o le han dado arranques de coraje?', 'orden' => 2],
            ['seccion_id' => $seccion4_g1->id, 'pregunta_tipo_id' => $tipoSINO->id, 'texto_pregunta' => '¿Ha tenido dificultad para concentrarse?', 'orden' => 3],
            ['seccion_id' => $seccion4_g1->id, 'pregunta_tipo_id' => $tipoSINO->id, 'texto_pregunta' => '¿Ha estado nervioso o permanentemente en alerta?', 'orden' => 4],
            ['seccion_id' => $seccion4_g1->id, 'pregunta_tipo_id' => $tipoSINO->id, 'texto_pregunta' => '¿Se ha sobresaltado fácilmente por cualquier cosa?', 'orden' => 5],
        ]);

        // -------------------------------------
        // --- 5. POBLAR GUÍA DE REFERENCIA II ---
        // -------------------------------------

        // Secciones Guía II
        $seccion1_g2 = Seccion::create(['cuestionario_id' => $guia2->id, 'titulo' => 'Ambiente de trabajo', 'descripcion' => 'Para responder a las preguntas siguientes, piense en las condiciones de su centro de trabajo.', 'orden' => 1]);
        $seccion2_g2 = Seccion::create(['cuestionario_id' => $guia2->id, 'titulo' => 'Factores propios de la actividad', 'orden' => 2]);
        $seccion3_g2 = Seccion::create(['cuestionario_id' => $guia2->id, 'titulo' => 'Organización del tiempo de trabajo', 'orden' => 3]);
        $seccion4_g2 = Seccion::create(['cuestionario_id' => $guia2->id, 'titulo' => 'Liderazgo y relaciones en el trabajo', 'orden' => 4]);

        // Preguntas Guía II - Sección 1
        Pregunta::insert([
            ['seccion_id' => $seccion1_g2->id, 'pregunta_tipo_id' => $tipoLikert5->id, 'texto_pregunta' => 'Mi trabajo me exige hacer mucho esfuerzo físico', 'orden' => 1],
            ['seccion_id' => $seccion1_g2->id, 'pregunta_tipo_id' => $tipoLikert5->id, 'texto_pregunta' => 'Me preocupa sufrir un accidente en mi trabajo', 'orden' => 2],
            ['seccion_id' => $seccion1_g2->id, 'pregunta_tipo_id' => $tipoLikert5->id, 'texto_pregunta' => 'Considero que las actividades que realizo son peligrosas', 'orden' => 3],
            ['seccion_id' => $seccion1_g2->id, 'pregunta_tipo_id' => $tipoLikert5->id, 'texto_pregunta' => 'Por la cantidad de trabajo que tengo debo quedarme tiempo adicional a mi turno', 'orden' => 4],
            ['seccion_id' => $seccion1_g2->id, 'pregunta_tipo_id' => $tipoLikert5->id, 'texto_pregunta' => 'Por la cantidad de trabajo que tengo debo trabajar sin parar', 'orden' => 5],
            ['seccion_id' => $seccion1_g2->id, 'pregunta_tipo_id' => $tipoLikert5->id, 'texto_pregunta' => 'Considero que es necesario mantener un ritmo de trabajo acelerado', 'orden' => 6],
            ['seccion_id' => $seccion1_g2->id, 'pregunta_tipo_id' => $tipoLikert5->id, 'texto_pregunta' => 'Mi trabajo exige que esté muy concentrado', 'orden' => 7],
            ['seccion_id' => $seccion1_g2->id, 'pregunta_tipo_id' => $tipoLikert5->id, 'texto_pregunta' => 'Mi trabajo requiere que memorice mucha información', 'orden' => 8],
            ['seccion_id' => $seccion1_g2->id, 'pregunta_tipo_id' => $tipoLikert5->id, 'texto_pregunta' => 'Mi trabajo exige que atienda varios asuntos al mismo tiempo', 'orden' => 9],
        ]);

        // Preguntas Guía II - Sección 2
        Pregunta::insert([
            ['seccion_id' => $seccion2_g2->id, 'pregunta_tipo_id' => $tipoLikert5->id, 'texto_pregunta' => 'En mi trabajo soy responsable de cosas de mucho valor', 'orden' => 10],
            ['seccion_id' => $seccion2_g2->id, 'pregunta_tipo_id' => $tipoLikert5->id, 'texto_pregunta' => 'Respondo ante mi jefe por los resultados de toda mi área de trabajo', 'orden' => 11],
            ['seccion_id' => $seccion2_g2->id, 'pregunta_tipo_id' => $tipoLikert5->id, 'texto_pregunta' => 'En mi trabajo me dan órdenes contradictorias', 'orden' => 12],
            ['seccion_id' => $seccion2_g2->id, 'pregunta_tipo_id' => $tipoLikert5->id, 'texto_pregunta' => 'Considero que en mi trabajo me piden hacer cosas innecesarias', 'orden' => 13],
            ['seccion_id' => $seccion2_g2->id, 'pregunta_tipo_id' => $tipoLikert5->id, 'texto_pregunta' => 'Mi trabajo me permite desarrollar nuevas habilidades', 'orden' => 14],
            ['seccion_id' => $seccion2_g2->id, 'pregunta_tipo_id' => $tipoLikert5->id, 'texto_pregunta' => 'Puedo tomar decisiones en mi trabajo', 'orden' => 15],
            ['seccion_id' => $seccion2_g2->id, 'pregunta_tipo_id' => $tipoLikert5->id, 'texto_pregunta' => 'En mi trabajo puedo aplicar mis conocimientos', 'orden' => 16],
            ['seccion_id' => $seccion2_g2->id, 'pregunta_tipo_id' => $tipoLikert5->id, 'texto_pregunta' => 'Mis jefes tienen en cuenta mis puntos de vista', 'orden' => 17],
            ['seccion_id' => $seccion2_g2->id, 'pregunta_tipo_id' => $tipoLikert5->id, 'texto_pregunta' => 'Me informan sobre lo que hago bien en mi trabajo', 'orden' => 18],
            ['seccion_id' => $seccion2_g2->id, 'pregunta_tipo_id' => $tipoLikert5->id, 'texto_pregunta' => 'En mi trabajo me explican claramente cuáles son mis funciones', 'orden' => 19],
            ['seccion_id' => $seccion2_g2->id, 'pregunta_tipo_id' => $tipoLikert5->id, 'texto_pregunta' => 'Me informan con anticipación sobre los cambios en mi trabajo', 'orden' => 20],
            ['seccion_id' => $seccion2_g2->id, 'pregunta_tipo_id' => $tipoLikert5->id, 'texto_pregunta' => 'Las personas que me rodean en el trabajo me ayudan cuando lo necesito', 'orden' => 21],
            ['seccion_id' => $seccion2_g2->id, 'pregunta_tipo_id' => $tipoLikert5->id, 'texto_pregunta' => 'En mi trabajo puedo confiar en mis compañeros', 'orden' => 22],
        ]);

        // Preguntas Guía II - Sección 3
        Pregunta::insert([
            ['seccion_id' => $seccion3_g2->id, 'pregunta_tipo_id' => $tipoLikert5->id, 'texto_pregunta' => 'Trabajo horas extras más de tres veces a la semana', 'orden' => 23],
            ['seccion_id' => $seccion3_g2->id, 'pregunta_tipo_id' => $tipoLikert5->id, 'texto_pregunta' => 'Mi trabajo me exige laborar en días de descanso, festivos o fines de semana', 'orden' => 24],
            ['seccion_id' => $seccion3_g2->id, 'pregunta_tipo_id' => $tipoLikert5->id, 'texto_pregunta' => 'Considero que el tiempo en el trabajo es mucho y perjudica mis actividades familiares o personales', 'orden' => 25],
            ['seccion_id' => $seccion3_g2->id, 'pregunta_tipo_id' => $tipoLikert5->id, 'texto_pregunta' => 'Pienso en las actividades familiares o personales cuando estoy en mi trabajo', 'orden' => 26],
            ['seccion_id' => $seccion3_g2->id, 'pregunta_tipo_id' => $tipoLikert5->id, 'texto_pregunta' => 'Pienso en mi trabajo cuando estoy en casa', 'orden' => 27],
        ]);

        // Preguntas Guía II - Sección 4
        Pregunta::insert([
            ['seccion_id' => $seccion4_g2->id, 'pregunta_tipo_id' => $tipoLikert5->id, 'texto_pregunta' => 'Mi jefe me ayuda a organizar mejor el trabajo', 'orden' => 28],
            ['seccion_id' => $seccion4_g2->id, 'pregunta_tipo_id' => $tipoLikert5->id, 'texto_pregunta' => 'Mi jefe tiene en cuenta mis necesidades personales o familiares', 'orden' => 29],
            ['seccion_id' => $seccion4_g2->id, 'pregunta_tipo_id' => $tipoLikert5->id, 'texto_pregunta' => 'Mi jefe me ayuda a solucionar los problemas que se presentan en el trabajo', 'orden' => 30],
            ['seccion_id' => $seccion4_g2->id, 'pregunta_tipo_id' => $tipoLikert5->id, 'texto_pregunta' => 'Mi jefe permite que los trabajadores participen en la toma de decisiones', 'orden' => 31],
            ['seccion_id' => $seccion4_g2->id, 'pregunta_tipo_id' => $tipoLikert5->id, 'texto_pregunta' => 'Mi jefe anima a los trabajadores para que den lo mejor de sí mismos', 'orden' => 32],
            ['seccion_id' => $seccion4_g2->id, 'pregunta_tipo_id' => $tipoLikert5->id, 'texto_pregunta' => 'Mi jefe comunica a los trabajadores lo que espera de ellos', 'orden' => 33],
            ['seccion_id' => $seccion4_g2->id, 'pregunta_tipo_id' => $tipoLikert5->id, 'texto_pregunta' => 'Mi jefe me trata de manera injusta', 'orden' => 34],
            ['seccion_id' => $seccion4_g2->id, 'pregunta_tipo_id' => $tipoLikert5->id, 'texto_pregunta' => 'En mi trabajo existe la posibilidad de desarrollarme', 'orden' => 35],
            ['seccion_id' => $seccion4_g2->id, 'pregunta_tipo_id' => $tipoLikert5->id, 'texto_pregunta' => 'La empresa me da la oportunidad de capacitarme', 'orden' => 36],
            ['seccion_id' => $seccion4_g2->id, 'pregunta_tipo_id' => $tipoLikert5->id, 'texto_pregunta' => 'Siento que mi trabajo es estable', 'orden' => 37],
            ['seccion_id' => $seccion4_g2->id, 'pregunta_tipo_id' => $tipoLikert5->id, 'texto_pregunta' => 'En mi trabajo me siento satisfecho', 'orden' => 38],
            ['seccion_id' => $seccion4_g2->id, 'pregunta_tipo_id' => $tipoLikert5->id, 'texto_pregunta' => 'Considero que mi trabajo es importante', 'orden' => 39],
            ['seccion_id' => $seccion4_g2->id, 'pregunta_tipo_id' => $tipoLikert5->id, 'texto_pregunta' => 'Atiendo clientes o usuarios muy enojados', 'orden' => 40],
            ['seccion_id' => $seccion4_g2->id, 'pregunta_tipo_id' => $tipoLikert5->id, 'texto_pregunta' => 'Mi trabajo me produce miedo (por ejemplo, por asaltos o violencia)', 'orden' => 41],
            ['seccion_id' => $seccion4_g2->id, 'pregunta_tipo_id' => $tipoLikert5->id, 'texto_pregunta' => 'En mi trabajo debo tratar con clientes o usuarios que me insultan o humillan', 'orden' => 42],
            ['seccion_id' => $seccion4_g2->id, 'pregunta_tipo_id' => $tipoLikert5->id, 'texto_pregunta' => 'En mi trabajo mis compañeros me faltan al respeto', 'orden' => 43],
            ['seccion_id' => $seccion4_g2->id, 'pregunta_tipo_id' => $tipoLikert5->id, 'texto_pregunta' => 'En mi trabajo he sufrido acoso sexual', 'orden' => 44],
            ['seccion_id' => $seccion4_g2->id, 'pregunta_tipo_id' => $tipoLikert5->id, 'texto_pregunta' => 'En mi trabajo he sufrido acoso psicológico (me humillan, intimidan, etc.)', 'orden' => 45],
            ['seccion_id' => $seccion4_g2->id, 'pregunta_tipo_id' => $tipoLikert5->id, 'texto_pregunta' => 'En mi trabajo me han amenazado', 'orden' => 46],
        ]);


        // -------------------------------------
        // --- 6. POBLAR GUÍA DE REFERENCIA III ---
        // -------------------------------------

        // Secciones Guía III
        $seccion1_g3 = Seccion::create(['cuestionario_id' => $guia3->id, 'titulo' => 'Ambiente de trabajo', 'descripcion' => 'Las preguntas siguientes están relacionadas con las condiciones del lugar donde trabaja.', 'orden' => 1]);
        $seccion2_g3 = Seccion::create(['cuestionario_id' => $guia3->id, 'titulo' => 'Factores propios de la actividad', 'descripcion' => 'Las preguntas siguientes están relacionadas con las actividades que realiza en su trabajo y las responsabilidades que tiene.', 'orden' => 2]);
        $seccion3_g3 = Seccion::create(['cuestionario_id' => $guia3->id, 'titulo' => 'Organización del tiempo de trabajo', 'descripcion' => 'Las preguntas siguientes están relacionadas con el tiempo que dedica a su trabajo y sus responsabilidades familiares.', 'orden' => 3]);
        $seccion4_g3 = Seccion::create(['cuestionario_id' => $guia3->id, 'titulo' => 'Liderazgo y relaciones en el trabajo', 'descripcion' => 'Las preguntas siguientes están relacionadas con sus jefes y compañeros de trabajo.', 'orden' => 4]);
        $seccion5_g3 = Seccion::create(['cuestionario_id' => $guia3->id, 'titulo' => 'Entorno organizacional', 'descripcion' => 'Las preguntas siguientes están relacionadas con la forma en que la empresa valora su trabajo y se comunica con usted.', 'orden' => 5]);

        // Preguntas Guía III - Sección 1
        Pregunta::insert([
            ['seccion_id' => $seccion1_g3->id, 'pregunta_tipo_id' => $tipoLikert5->id, 'texto_pregunta' => 'El espacio donde trabajo me permite realizar mis actividades de manera segura e higiénica', 'orden' => 1],
            ['seccion_id' => $seccion1_g3->id, 'pregunta_tipo_id' => $tipoLikert5->id, 'texto_pregunta' => 'Mi trabajo me exige hacer mucho esfuerzo físico', 'orden' => 2],
            ['seccion_id' => $seccion1_g3->id, 'pregunta_tipo_id' => $tipoLikert5->id, 'texto_pregunta' => 'Me preocupa sufrir un accidente en mi trabajo', 'orden' => 3],
            ['seccion_id' => $seccion1_g3->id, 'pregunta_tipo_id' => $tipoLikert5->id, 'texto_pregunta' => 'Considero que en mi trabajo se aplican las normas de seguridad y salud', 'orden' => 4],
            ['seccion_id' => $seccion1_g3->id, 'pregunta_tipo_id' => $tipoLikert5->id, 'texto_pregunta' => 'Considero que las actividades que realizo son peligrosas', 'orden' => 5],
        ]);

        // Preguntas Guía III - Sección 2
        Pregunta::insert([
            ['seccion_id' => $seccion2_g3->id, 'pregunta_tipo_id' => $tipoLikert5->id, 'texto_pregunta' => 'Por la cantidad de trabajo que tengo debo quedarme tiempo adicional a mi turno', 'orden' => 6],
            ['seccion_id' => $seccion2_g3->id, 'pregunta_tipo_id' => $tipoLikert5->id, 'texto_pregunta' => 'Por la cantidad de trabajo que tengo debo trabajar sin parar', 'orden' => 7],
            ['seccion_id' => $seccion2_g3->id, 'pregunta_tipo_id' => $tipoLikert5->id, 'texto_pregunta' => 'Considero que es necesario mantener un ritmo de trabajo acelerado', 'orden' => 8],
            ['seccion_id' => $seccion2_g3->id, 'pregunta_tipo_id' => $tipoLikert5->id, 'texto_pregunta' => 'Mi trabajo exige que esté muy concentrado', 'orden' => 9],
            ['seccion_id' => $seccion2_g3->id, 'pregunta_tipo_id' => $tipoLikert5->id, 'texto_pregunta' => 'Mi trabajo requiere que memorice mucha información', 'orden' => 10],
            ['seccion_id' => $seccion2_g3->id, 'pregunta_tipo_id' => $tipoLikert5->id, 'texto_pregunta' => 'En mi trabajo tengo que tomar decisiones difíciles', 'orden' => 11],
            ['seccion_id' => $seccion2_g3->id, 'pregunta_tipo_id' => $tipoLikert5->id, 'texto_pregunta' => 'Mi trabajo exige que atienda varios asuntos al mismo tiempo', 'orden' => 12],
            ['seccion_id' => $seccion2_g3->id, 'pregunta_tipo_id' => $tipoLikert5->id, 'texto_pregunta' => 'En mi trabajo soy responsable de cosas de mucho valor', 'orden' => 13],
            ['seccion_id' => $seccion2_g3->id, 'pregunta_tipo_id' => $tipoLikert5->id, 'texto_pregunta' => 'Respondo ante mi jefe por los resultados de toda mi área de trabajo', 'orden' => 14],
            ['seccion_id' => $seccion2_g3->id, 'pregunta_tipo_id' => $tipoLikert5->id, 'texto_pregunta' => 'En mi trabajo me dan órdenes contradictorias', 'orden' => 15],
            ['seccion_id' => $seccion2_g3->id, 'pregunta_tipo_id' => $tipoLikert5->id, 'texto_pregunta' => 'Considero que en mi trabajo me piden hacer cosas innecesarias', 'orden' => 16],
            ['seccion_id' => $seccion2_g3->id, 'pregunta_tipo_id' => $tipoLikert5->id, 'texto_pregunta' => 'Mi trabajo me permite desarrollar nuevas habilidades', 'orden' => 17],
            ['seccion_id' => $seccion2_g3->id, 'pregunta_tipo_id' => $tipoLikert5->id, 'texto_pregunta' => 'Puedo tomar decisiones en mi trabajo', 'orden' => 18],
            ['seccion_id' => $seccion2_g3->id, 'pregunta_tipo_id' => $tipoLikert5->id, 'texto_pregunta' => 'En mi trabajo puedo aplicar mis conocimientos', 'orden' => 19],
            ['seccion_id' => $seccion2_g3->id, 'pregunta_tipo_id' => $tipoLikert5->id, 'texto_pregunta' => 'Mis jefes tienen en cuenta mis puntos de vista', 'orden' => 20],
            ['seccion_id' => $seccion2_g3->id, 'pregunta_tipo_id' => $tipoLikert5->id, 'texto_pregunta' => 'Me informan sobre lo que hago bien en mi trabajo', 'orden' => 21],
            ['seccion_id' => $seccion2_g3->id, 'pregunta_tipo_id' => $tipoLikert5->id, 'texto_pregunta' => 'En mi trabajo me explican claramente cuáles son mis funciones', 'orden' => 22],
            ['seccion_id' => $seccion2_g3->id, 'pregunta_tipo_id' => $tipoLikert5->id, 'texto_pregunta' => 'Me informan con anticipación sobre los cambios en mi trabajo', 'orden' => 23],
            ['seccion_id' => $seccion2_g3->id, 'pregunta_tipo_id' => $tipoLikert5->id, 'texto_pregunta' => 'Las personas que me rodean en el trabajo me ayudan cuando lo necesito', 'orden' => 24],
            ['seccion_id' => $seccion2_g3->id, 'pregunta_tipo_id' => $tipoLikert5->id, 'texto_pregunta' => 'En mi trabajo puedo confiar en mis compañeros', 'orden' => 25],
        ]);

        // Preguntas Guía III - Sección 3
        Pregunta::insert([
            ['seccion_id' => $seccion3_g3->id, 'pregunta_tipo_id' => $tipoLikert5->id, 'texto_pregunta' => 'Trabajo horas extras más de tres veces a la semana', 'orden' => 26],
            ['seccion_id' => $seccion3_g3->id, 'pregunta_tipo_id' => $tipoLikert5->id, 'texto_pregunta' => 'Mi trabajo me exige laborar en días de descanso, festivos o fines de semana', 'orden' => 27],
            ['seccion_id' => $seccion3_g3->id, 'pregunta_tipo_id' => $tipoLikert5->id, 'texto_pregunta' => 'Considero que el tiempo en el trabajo es mucho y perjudica mis actividades familiares o personales', 'orden' => 28],
            ['seccion_id' => $seccion3_g3->id, 'pregunta_tipo_id' => $tipoLikert5->id, 'texto_pregunta' => 'Tengo que atender asuntos de trabajo cuando estoy en casa', 'orden' => 29],
            ['seccion_id' => $seccion3_g3->id, 'pregunta_tipo_id' => $tipoLikert5->id, 'texto_pregunta' => 'Pienso en las actividades familiares o personales cuando estoy en mi trabajo', 'orden' => 30],
            ['seccion_id' => $seccion3_g3->id, 'pregunta_tipo_id' => $tipoLikert5->id, 'texto_pregunta' => 'Pienso en mi trabajo cuando estoy en casa', 'orden' => 31],
        ]);

        // Preguntas Guía III - Sección 4
        Pregunta::insert([
            ['seccion_id' => $seccion4_g3->id, 'pregunta_tipo_id' => $tipoLikert5->id, 'texto_pregunta' => 'Mi jefe me ayuda a organizar mejor el trabajo', 'orden' => 32],
            ['seccion_id' => $seccion4_g3->id, 'pregunta_tipo_id' => $tipoLikert5->id, 'texto_pregunta' => 'Mi jefe tiene en cuenta mis necesidades personales o familiares', 'orden' => 33],
            ['seccion_id' => $seccion4_g3->id, 'pregunta_tipo_id' => $tipoLikert5->id, 'texto_pregunta' => 'Mi jefe me ayuda a solucionar los problemas que se presentan en el trabajo', 'orden' => 34],
            ['seccion_id' => $seccion4_g3->id, 'pregunta_tipo_id' => $tipoLikert5->id, 'texto_pregunta' => 'Mi jefe permite que los trabajadores participen en la toma de decisiones', 'orden' => 35],
            ['seccion_id' => $seccion4_g3->id, 'pregunta_tipo_id' => $tipoLikert5->id, 'texto_pregunta' => 'Mi jefe anima a los trabajadores para que den lo mejor de sí mismos', 'orden' => 36],
            ['seccion_id' => $seccion4_g3->id, 'pregunta_tipo_id' => $tipoLikert5->id, 'texto_pregunta' => 'Mi jefe comunica a los trabajadores lo que espera de ellos', 'orden' => 37],
            ['seccion_id' => $seccion4_g3->id, 'pregunta_tipo_id' => $tipoLikert5->id, 'texto_pregunta' => 'Mi jefe me trata de manera injusta', 'orden' => 38],
            ['seccion_id' => $seccion4_g3->id, 'pregunta_tipo_id' => $tipoLikert5->id, 'texto_pregunta' => 'Mi jefe delega responsabilidades en mí', 'orden' => 39],
            ['seccion_id' => $seccion4_g3->id, 'pregunta_tipo_id' => $tipoLikert5->id, 'texto_pregunta' => 'Mi jefe demuestra conocer bien el trabajo', 'orden' => 40],
            ['seccion_id' => $seccion4_g3->id, 'pregunta_tipo_id' => $tipoLikert5->id, 'texto_pregunta' => 'Mi jefe me trata con amabilidad', 'orden' => 41],
            ['seccion_id' => $seccion4_g3->id, 'pregunta_tipo_id' => $tipoLikert5->id, 'texto_pregunta' => 'Mi jefe escucha mis opiniones', 'orden' => 42],
            ['seccion_id' => $seccion4_g3->id, 'pregunta_tipo_id' => $tipoLikert5->id, 'texto_pregunta' => 'Mi jefe me trata con confianza', 'orden' => 43],
            ['seccion_id' => $seccion4_g3->id, 'pregunta_tipo_id' => $tipoLikert5->id, 'texto_pregunta' => 'Mis compañeros me ayudan cuando tengo dificultades', 'orden' => 44],
            ['seccion_id' => $seccion4_g3->id, 'pregunta_tipo_id' => $tipoLikert5->id, 'texto_pregunta' => 'Mis compañeros me tratan con amabilidad', 'orden' => 45],
            ['seccion_id' => $seccion4_g3->id, 'pregunta_tipo_id' => $tipoLikert5->id, 'texto_pregunta' => 'Mis compañeros me escuchan cuando tengo problemas', 'orden' => 46],
            ['seccion_id' => $seccion4_g3->id, 'pregunta_tipo_id' => $tipoLikert5->id, 'texto_pregunta' => 'Mis compañeros me tratan con confianza', 'orden' => 47],
        ]);

        // Preguntas Guía III - Sección 5
        Pregunta::insert([
            ['seccion_id' => $seccion5_g3->id, 'pregunta_tipo_id' => $tipoLikert5->id, 'texto_pregunta' => 'En mi trabajo existe la posibilidad de desarrollarme', 'orden' => 48],
            ['seccion_id' => $seccion5_g3->id, 'pregunta_tipo_id' => $tipoLikert5->id, 'texto_pregunta' => 'La empresa me da la oportunidad de capacitarme', 'orden' => 49],
            ['seccion_id' => $seccion5_g3->id, 'pregunta_tipo_id' => $tipoLikert5->id, 'texto_pregunta' => 'Siento que mi trabajo es estable', 'orden' => 50],
            ['seccion_id' => $seccion5_g3->id, 'pregunta_tipo_id' => $tipoLikert5->id, 'texto_pregunta' => 'En mi trabajo me siento satisfecho', 'orden' => 51],
            ['seccion_id' => $seccion5_g3->id, 'pregunta_tipo_id' => $tipoLikert5->id, 'texto_pregunta' => 'Considero que mi trabajo es importante', 'orden' => 52],
            ['seccion_id' => $seccion5_g3->id, 'pregunta_tipo_id' => $tipoLikert5->id, 'texto_pregunta' => 'Mis jefes me dan a conocer los resultados de mi trabajo', 'orden' => 53],
            ['seccion_id' => $seccion5_g3->id, 'pregunta_tipo_id' => $tipoLikert5->id, 'texto_pregunta' => 'Mis jefes me explican cómo debo realizar mi trabajo', 'orden' => 54],
            ['seccion_id' => $seccion5_g3->id, 'pregunta_tipo_id' => $tipoLikert5->id, 'texto_pregunta' => 'Mis jefes me informan sobre los cambios en el trabajo', 'orden' => 55],
            ['seccion_id' => $seccion5_g3->id, 'pregunta_tipo_id' => $tipoLikert5->id, 'texto_pregunta' => 'La empresa me informa sobre los logros y avances', 'orden' => 56],
            ['seccion_id' => $seccion5_g3->id, 'pregunta_tipo_id' => $tipoLikert5->id, 'texto_pregunta' => 'La empresa me informa sobre su situación financiera', 'orden' => 57],
            ['seccion_id' => $seccion5_g3->id, 'pregunta_tipo_id' => $tipoLikert5->id, 'texto_pregunta' => 'En la empresa se promueve el sentido de pertenencia', 'orden' => 58],
            ['seccion_id' => $seccion5_g3->id, 'pregunta_tipo_id' => $tipoLikert5->id, 'texto_pregunta' => 'En la empresa se toman en cuenta mis opiniones', 'orden' => 59],
            ['seccion_id' => $seccion5_g3->id, 'pregunta_tipo_id' => $tipoLikert5->id, 'texto_pregunta' => 'La empresa me informa sobre el desempeño de mi trabajo', 'orden' => 60],
            ['seccion_id' => $seccion5_g3->id, 'pregunta_tipo_id' => $tipoLikert5->id, 'texto_pregunta' => 'La empresa me paga a tiempo mi salario', 'orden' => 61],
            ['seccion_id' => $seccion5_g3->id, 'pregunta_tipo_id' => $tipoLikert5->id, 'texto_pregunta' => 'El pago que recibo es el que merezco por mi trabajo', 'orden' => 62],
            ['seccion_id' => $seccion5_g3->id, 'pregunta_tipo_id' => $tipoLikert5->id, 'texto_pregunta' => 'Mi trabajo me permite tener tiempo para mis actividades personales y familiares', 'orden' => 63],
            ['seccion_id' => $seccion5_g3->id, 'pregunta_tipo_id' => $tipoLikert5->id, 'texto_pregunta' => 'La empresa apoya mis necesidades personales y familiares', 'orden' => 64],
            ['seccion_id' => $seccion5_g3->id, 'pregunta_tipo_id' => $tipoLikert5->id, 'texto_pregunta' => 'Atiendo clientes o usuarios muy enojados', 'orden' => 65],
            ['seccion_id' => $seccion5_g3->id, 'pregunta_tipo_id' => $tipoLikert5->id, 'texto_pregunta' => 'Mi trabajo me produce miedo (por ejemplo, por asaltos o violencia)', 'orden' => 66],
            ['seccion_id' => $seccion5_g3->id, 'pregunta_tipo_id' => $tipoLikert5->id, 'texto_pregunta' => 'En mi trabajo debo tratar con clientes o usuarios que me insultan o humillan', 'orden' => 67],
            ['seccion_id' => $seccion5_g3->id, 'pregunta_tipo_id' => $tipoLikert5->id, 'texto_pregunta' => 'En mi trabajo mis compañeros me faltan al respeto', 'orden' => 68],
            ['seccion_id' => $seccion5_g3->id, 'pregunta_tipo_id' => $tipoLikert5->id, 'texto_pregunta' => 'En mi trabajo he sufrido acoso sexual', 'orden' => 69],
            ['seccion_id' => $seccion5_g3->id, 'pregunta_tipo_id' => $tipoLikert5->id, 'texto_pregunta' => 'En mi trabajo he sufrido acoso psicológico (me humillan, intimidan, etc.)', 'orden' => 70],
            ['seccion_id' => $seccion5_g3->id, 'pregunta_tipo_id' => $tipoLikert5->id, 'texto_pregunta' => 'En mi trabajo me han amenazado', 'orden' => 71],
            ['seccion_id' => $seccion5_g3->id, 'pregunta_tipo_id' => $tipoLikert5->id, 'texto_pregunta' => 'En mi trabajo he sido ignorado o excluido por mis compañeros o jefes', 'orden' => 72],
        ]);

    }
}
