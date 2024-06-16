<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Type;

class TypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Type::create([
            'type_name' => 'Drawing art',
            'details' => 'Drawing is an expression of ideas and feelings. It is done by creating a two-dimensional aesthetic image in a visual language. This language is expressed in different shapes and methods, lines and colors, resulting in volumes, light and movement on a flat surface. All of these elements are combined in an expressive way, in order to produce phenomena. Real, or supernatural, and to show completely abstract visual relationships as well. Oil, acrylic, watercolor, ink, and other watercolor paints are used in this, so that the painter chooses a specific form, such as: a mural, a painting, or any variety of modern forms. All of these options, in addition to the artist’s own style, combine to achieve a unique and different visual image.',
        ]);

        Type::create([
            'type_name' => 'Graphic design',
            'details' => 'Graphic design is a creative process that involves using visual elements to communicate specific ideas, messages, and information in a simple and attractive manner. Graphic design is a form of visual communication that combines art, technology, and design. To create content that is aesthetically and functionally pleasing. Today, graphic design is used in a very wide range of fields, including advertising, marketing, publishing, brand identity design, websites, business card design, street signs, and many more.Graphic art can be applied in the form of original drawings, lines, and patterns to the decorative arts, if applied to furniture, textiles, ceramics, interiors, and in architecture.',
        ]);

        Type::create([
            'type_name' => 'Photography',
            'details' => 'Photography, in its broadest sense, is the process of expression using the application of color on any surface. It is one of the arts that has influenced and influenced society the most. It mixed with plastic artistic movements such as: the Classical School, the Romantic School, the Realist School, the Expressionist School, the Impressionist Movement, the Surrealist School, then the Cubist School and others... It includes: oil photography, acrylic photography, watercolor photography, color painting, and mural photography. It is a type of visual art that consists of copying a scene as it is and transforming it into an image that can be seen visually in several ways. Such as photography using photographic devices, or drawing using brush, pens, colors, etc.',
        ]);

        Type::create([
            'type_name' => 'Sculpture art',
            'details' => 'The art of sculpture is one of the forms of visual arts that is based on the formation of three-dimensional artistic pieces from solid materials. The sculptures can be standing objects on their own, or they can be engravings and decorations on surfaces of various kinds, such as wood, clay, glass, stone, and wax. And any other solid materials, and the art of sculpture is not limited to that only, but extends to shaping materials through casting, manufacturing, welding or combining them with each other in multiple ways. It is worth noting that the art of sculpture is one of the oldest types of arts, and it is also one of the arts that develops and grows over time, and expands the scope of its activities, as the art of sculpture existing today differs from the art of sculpture in a period a few decades ago, which was based on imitating functional representational forms. , similar to human and non-human life such as books, buildings and utensils, while modern sculpture shifted its focus towards the spatial aspect of forms.',
        ]);

        Type::create([
            'type_name' => 'Printing art',
            'details' => 'Printing on paper is a form of visual art that relies on transferring a drawing from a template made of wood, glass, or metal, on which this drawing was previously designed using chemical materials, to be transferred to another surface using different printing techniques. This surface is often made of paper or Cloth. Types include water-based printing, lithography, mezzotint, and others. It is the method by which colored patterns or drawings can be obtained in different ways on various known types of fabric, such as cotton, wool, natural silk, linen, or various manufactured fabrics. This method is done using wooden, paper, or other templates, and the drawings and engravings vary widely. Materials used and printing method used.',
        ]);

        Type::create([
            'type_name' => 'Mural art',
            'details' => 'Mural art is one of the oldest forms of human art. It means drawing an artistic painting on a wall or ceiling, and it can be applied in a public place or inside buildings. The mural is usually large in size, and drawing it requires high artistic expertise. The painter forms it on the wall according to the geometric shape of the wall so that the painting and the wall stand out together as one unit. The use of unique designs and thematic treatment in wall art gives it a different character from other forms of visual art, in addition to giving the painting a different feeling in the spatial proportions of the building, as it is closer to three-dimensional drawing. Mural painting is inherently different from all other forms of collage, being organically linked to architecture. The use of colour, design and topical treatment can radically change the sense of spatial proportions of a building. Thus, the mural is the only three-dimensional form of pictorial drawing, due to its ability to modify the space applied to it.',
        ]);

        Type::create([
            'type_name' => 'Mosaic',
            'details' => 'Mosaic expresses a type of fine art, which shows precision and beauty at the same time. The so-called mosaic is formed when a surface is decorated by lining up small-sized pieces that rarely have regular dimensions next to each other, so that the pieces used can be In the art of mosaic made of stones, glass, metals, peels, tiles, and other materials, a space is prepared to install the pieces next to each other according to a specific layout to produce the final shape, using adhesive materials to collect the pieces and install them in their places.',
        ]);
    }
}
