<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        //        Category::factory(5)->create();
        //        Category::factory(10)->create();
        //        Category::factory(10)->create();
        //        Category::factory(10)->create();

        // Función auxiliar recursiva para insertar las categorías
        $insertCategoryTree = static function (array $nodes, ?int $parentId = null) use (&$insertCategoryTree) {
            foreach ($nodes as $name => $children) {
                $category = Category::create([
                    'name'        => $name,
                    'description' => $name,
                    'parent_id'   => $parentId,
                ]);

                if (is_array($children)) {
                    $insertCategoryTree($children, $category->id);
                }
            }
        };

        // Estructura del árbol
        $categories = [
            'Maquinarias' => [
                'Pesadas' => [
                    'Excavadoras' => [
                        'Excavadoras sobre orugas' => [],
                        'Excavadoras sobre ruedas' => [],
                    ],
                    'Retroexcavadoras'     => [],
                    'Cargadores frontales' => [],
                    'Bulldozers'           => [],
                    'Compactadores'        => [
                        'Compactadores de rodillo'  => [],
                        'Compactadores vibratorios' => [],
                    ],
                    'Grúas' => [
                        'Grúas móviles' => [],
                        'Grúas torre'   => [],
                    ],
                ],
                'Livianas' => [
                    'Minicargadores'        => [],
                    'Martillos demoledores' => [
                        'Eléctricos' => [],
                        'Neumáticos' => [],
                    ],
                    'Compactadoras manuales' => [],
                    'Andamios'               => [
                        'Fijos'    => [],
                        'Rodantes' => [],
                    ],
                    'Mezcladoras de concreto' => [],
                    'Generadores eléctricos'  => [
                        'Gasolina' => [],
                        'Diésel'   => [],
                    ],
                ],
                'Equipos de elevación' => [
                    'Plataformas elevadoras' => [
                        'Tipo tijera'           => [],
                        'Tipo brazo articulado' => [],
                    ],
                    'Montacargas'           => [],
                    'Torres de iluminación' => [],
                ],
                'Equipos para mantenimiento' => [
                    'Hidrolavadoras'           => [],
                    'Aspiradoras industriales' => [],
                    'Equipos de pintura'       => [],
                ],
                'Transporte y logística' => [
                    'Camiones volquetes' => [],
                    'Camionetas 4x4'     => [],
                    'Remolques'          => [],
                ],
            ],
        ];

        $insertCategoryTree($categories);
    }
}
