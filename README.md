# DevelNext

> GUI and IDE for php based on jphp, for beginners only.

![DevelNext Logo](https://github.com/jphp-compiler/develnext/raw/master/develnext/src/.data/img/splash.png)

---

**Dependencies**

- jphp 0.9.+ (not published yet, you can build it from the jphp sources!)
- java 1.8+ (jre)
- gradle 2.4+
- launch4j 3.8

### How to get the DevelNext IDE distrubutive?

Install JDK, clone repo and use the following console commands:

```
cd /path/to/repo

// fetch all submodules (jphp, richtextfx, etc.)
git submodule update --init

// install rich text fx
cd 3rd-party/RichTextFX
./gradlew install

// install jphp
cd ../3rd-party/jphp
./gradlew install

cd ../../

// for windows
./gradlew distIdeWindows

// for linux
./gradlew distIdeLinux
```

You can find the builded distrubutive in `develnext/build/install/develnext`.

### License

Under MPL 2.0 (https://www.mozilla.org/MPL/2.0/)

### Как получить дистрибутив DevelNext?

Установите JDK (Java), склонируйте репозиторий и используйте следующие консольные команды:

```
cd /path/to/repo

// fetch all submodules (jphp, richtextfx, etc.)
git submodule update --init

// install rich text fx
cd 3rd-party/RichTextFX
./gradlew install

// install jphp
cd ../3rd-party/jphp
./gradlew install

// install wizard-framework for web
cd ../3rd-party/wizard-framework
./gradlew install

cd ../../

// for windows
./gradlew distIdeWindows

// for linux
./gradlew distIdeLinux
```

Найти собранный дистрибутив можно будет в папке `develnext/build/install/develnext`.

Если проект не собирается, значит на текущем этапе в develnext используется еще неопубликованная версия jphp,
поэтому ее нужно собрать вручную с нужной ветки (см. версию), выполнив команду `gradlew install` в папке исходников jphp.

### Лицензия

Under MPL 2.0 (https://www.mozilla.org/MPL/2.0/)

*Обязательно*

  - Не закрывать исходники продукта
  - Прикладывать тексты лицензии
   
*Разрешено*

  - Комерческое использование
  - Распространение
  - Модификация
  - Патентные гарантия
  - Приватное использование
  - Саб-лицензирование
   
*Запрещено*
  
  - Нести ответственность
  - Использование торговой марки продукта (названия и логотипов)
