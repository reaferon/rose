<?php
/**
 * @copyright 2017 Roman Parpalak
 * @license   MIT
 */

namespace S2\Rose\Test;

use Codeception\Test\Unit;
use S2\Rose\Entity\Indexable;
use S2\Rose\Entity\Query;
use S2\Rose\Finder;
use S2\Rose\Indexer;
use S2\Rose\SnippetBuilder;
use S2\Rose\Stemmer\PorterStemmerRussian;
use S2\Rose\Storage\Database\PdoStorage;

/**
 * Class SnippetBuilderTest
 *
 * @group snippet
 */
class SnippetBuilderTest extends Unit
{
	/**
	 * @dataProvider indexableProvider
	 *
	 * @param Indexable[]           $indexables
	 */
	public function testSnippets(array $indexables) {
		global $s2_rose_test_db;

		$pdo = new \PDO($s2_rose_test_db['dsn'], $s2_rose_test_db['username'], $s2_rose_test_db['passwd']);
		$pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

		$readStorage =  new PdoStorage($pdo, 'test_');
		$writeStorage =  new PdoStorage($pdo, 'test_');

		$stemmer = new PorterStemmerRussian();
		$indexer = new Indexer($writeStorage, $stemmer);

		$writeStorage->erase();

		foreach ($indexables as $indexable) {
			$indexer->index($indexable);
		}

		$finder         = new Finder($readStorage, $stemmer);
		$snippetBuilder = new SnippetBuilder($stemmer);

		$snippetCallbackProvider = function (array $ids) use ($indexables) {
			$result = [];
			foreach ($indexables as $indexable) {
				if (in_array($indexable->getId(), $ids)) {
					$result[$indexable->getId()] = $indexable->getContent();
				}
			}

			return $result;
		};

		//$resultSet = $finder->find(new Query('предпосылки и развитие'));
		$resultSet = $finder->find(new Query('механическая природа'));
		$snippetBuilder->attachSnippets($resultSet, $snippetCallbackProvider);

		$this->assertEquals(
			'Если пренебречь малыми величинами, то видно, что <i>механическая</i> <i>природа</i> устойчиво требует большего внимания к анализу ошибок, которые дает устойчивый маховик.',
			$resultSet->getItems()['id_3']->getSnippet()
		);
	}

	public function indexableProvider()
	{
		$indexables = array(
			new Indexable('id_1', 'Почему неоднозначна борьба демократических и олигархических тенденций?', 'Политическое учение Платона, в первом приближении, формирует экзистенциальный социализм. Типология средств массовой коммуникации сохраняет эмпирический политический процесс в современной России. Доиндустриальный тип политической культуры, несмотря на внешние воздействия, неизбежен. Общеизвестно, что политическое учение Н. Макиавелли взаимно. Либерализм, особенно в условиях политической нестабильности, определяет либерализм. Постиндустриализм неоднозначен.

Натуралистическая парадигма, короче говоря, ограничивает экзистенциальный референдум. Политический процесс в современной России определяет гуманизм. Иначе говоря, политическая культура практически представляет собой механизм власти.

Технология коммуникации обретает онтологический референдум, утверждает руководитель аппарата Правительства. Согласно теории Э.Тоффлера ("Шок будущего"), коллапс Советского Союза иллюстрирует экзистенциальный континентально-европейский тип политической культуры. Марксизм вызывает современный референдум. В данном случае можно согласиться с Данилевским, считавшим, что информационно-технологическая революция сохраняет экзистенциальный референдум.'),
			new Indexable('id_2', 'Анормальный предел последовательности: предпосылки и развитие', 'Функция выпуклая кверху вырождена. Функция многих переменных положительна. Экстремум функции, в первом приближении, восстанавливает абстрактный разрыв функции. Несмотря на сложности, аффинное преобразование реально отражает интеграл от функции, обращающейся в бесконечность вдоль линии. Теорема порождает интеграл от функции, обращающейся в бесконечность вдоль линии, откуда следует доказываемое равенство.

Линейное программирование, в первом приближении, необходимо и достаточно. Отсюда естественно следует, что интеграл от функции, имеющий конечный разрыв обуславливает тригонометрический интеграл по поверхности, явно демонстрируя всю чушь вышесказанного. В соответствии с законом больших чисел, интеграл Пуассона стремительно обуславливает положительный разрыв функции.

Доказательство, как следует из вышесказанного, последовательно. Тем не менее, достаточное условие сходимости проецирует скачок функции. Метод последовательных приближений, следовательно, реально создает график функции. Метод последовательных приближений определяет интеграл по бесконечной области. Длина вектора, как следует из вышесказанного, неоднозначна. Геодезическая линия нейтрализует интеграл Фурье, как и предполагалось.'),
			new Indexable('id_3', 'Почему апериодичен маховик?', 'Внешнее кольцо позволяет пренебречь колебаниями корпуса, хотя развития этого в любом случае требует угол крена, поэтому энергия гироскопического маятника на неподвижной оси остаётся неизменной. Если основание движется с постоянным ускорением, проекция угловых скоростей вращает колебательный успокоитель качки. Абсолютно твёрдое тело заставляет иначе взглянуть на то, что такое объект. В самом общем случае маховик заставляет перейти к более сложной системе дифференциальных уравнений, если добавить устойчивый гиротахометр. Система координат, несмотря на внешние воздействия, трансформирует силовой трёхосный гироскопический стабилизатор.

Ошибка астатически даёт более простую систему дифференциальных уравнений, если исключить небольшой угол тангажа. Если пренебречь малыми величинами, то видно, что механическая природа устойчиво требует большего внимания к анализу ошибок, которые даёт устойчивый маховик. Исходя из уравнения Эйлера, прибор вертикально позволяет пренебречь колебаниями корпуса, хотя этого в любом случае требует поплавковый ньютонометр.

Уравнение возмущенного движения поступательно характеризует подвижный объект. Прецессия гироскопа косвенно интегрирует нестационарный вектор угловой скорости, изменяя направление движения. Угловая скорость, обобщая изложенное, неподвижно не входит своими составляющими, что очевидно, в силы нормальных реакций связей, так же как и кожух. Динамическое уравнение Эйлера, в силу третьего закона Ньютона, вращательно связывает ньютонометр, не забывая о том, что интенсивность диссипативных сил, характеризующаяся величиной коэффициента D, должна лежать в определённых пределах.')
		);

		return [
			'db'    => [$indexables],
		];
	}
}
