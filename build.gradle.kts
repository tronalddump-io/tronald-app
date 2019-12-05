import org.jetbrains.kotlin.gradle.tasks.KotlinCompile

plugins {
    id("com.energizedwork.webdriver-binaries") version "1.4"
    id("com.palantir.docker") version "0.22.1"
    id("io.spring.dependency-management") version "1.0.8.RELEASE"
    id("org.gradle.groovy")
    id("org.springframework.boot") version "2.2.1.RELEASE"

    kotlin("jvm") version "1.3.50"
    kotlin("plugin.spring") version "1.3.50"
}

val appName = "app"
val appVer by lazy { gitRev() }

group = "io.tronalddump"
version = appVer
java.sourceCompatibility = JavaVersion.VERSION_1_8

// versions
val chromeDriverVersion = "2.36"
val geckoDriverVersion = "0.24.0"
val seleniumVersion = "3.141.59"
val spockVersion = "1.3-groovy-2.5"

val sourceSets = the<SourceSetContainer>()

sourceSets {
    create("accTest") {
        java.srcDir(file("src/accTest/groovy"))
        resources.srcDir(file("src/accTest/resources"))
        compileClasspath += sourceSets["main"].output + configurations["testRuntimeClasspath"]
        runtimeClasspath += output + compileClasspath
    }
    create("integTest") {
        java.srcDir(file("src/integTest/groovy"))
        resources.srcDir(file("src/integTest/resources"))
        compileClasspath += sourceSets["main"].output + configurations["testRuntimeClasspath"]
        runtimeClasspath += output + compileClasspath
    }
}

repositories {
    mavenCentral()
}

dependencies {
    implementation("com.fasterxml.jackson.module:jackson-module-kotlin")
    implementation("nz.net.ultraq.thymeleaf:thymeleaf-layout-dialect")
    implementation("org.jetbrains.kotlin:kotlin-reflect")
    implementation("org.jetbrains.kotlin:kotlin-stdlib-jdk8")
    implementation("org.postgresql:postgresql:42.2.8")
    implementation("org.springframework.boot:spring-boot-starter-data-jpa")
    implementation("org.springframework.boot:spring-boot-starter-thymeleaf")
    implementation("org.springframework.boot:spring-boot-starter-web")
    implementation("org.springframework.hateoas:spring-hateoas:1.0.1.RELEASE")

    runtimeOnly("com.h2database:h2")
    runtimeOnly("org.springframework.boot:spring-boot-devtools")

    testImplementation("org.gebish:geb-spock:3.2")
    testImplementation("org.seleniumhq.selenium:selenium-chrome-driver:${seleniumVersion}")
    testImplementation("org.seleniumhq.selenium:selenium-firefox-driver:${seleniumVersion}")
    testImplementation("org.seleniumhq.selenium:selenium-java:${seleniumVersion}")
    testImplementation("org.spockframework:spock-core:${spockVersion}") { exclude(group = "org.codehaus.groovy") }
    testImplementation("org.spockframework:spock-spring:${spockVersion}")
    testImplementation("org.springframework.boot:spring-boot-starter-test") { exclude(group = "org.junit.vintage", module = "junit-vintage-engine") }
}

webdriverBinaries {
    chromedriver = chromeDriverVersion
    geckodriver = geckoDriverVersion
}

springBoot {
    buildInfo {
        properties {
            artifact = "$appName-$appVer.jar"
            version = appVer
            name = appName
        }
    }
}

tasks {
    bootJar {
        manifest {
            attributes("Multi-Release" to true)
        }

        archiveBaseName.set(appName)
        archiveVersion.set(appVer)

        if (project.hasProperty("archiveName")) {
            archiveFileName.set(project.properties["archiveName"] as String)
        }
    }

    docker {
        val bootJar = bootJar.get()
        val imageName = "$group/$appName"

        name = "$imageName:latest"
        tag("current", "$imageName:$appVer")
        tag("latest", "$imageName:latest")

        files(bootJar.archiveFile)
        setDockerfile(file("$projectDir/src/main/docker/Dockerfile"))
        buildArgs(
                mapOf("JAR_FILE" to bootJar.archiveFileName.get())
        )
    }

    register<Test>("acceptanceTest") {
        description = "Runs the acceptance tests."
        group = "verification"
        testClassesDirs = sourceSets["accTest"].output.classesDirs
        classpath = sourceSets["accTest"].runtimeClasspath

        outputs.upToDateWhen { false }

        systemProperty("geb.build.reportsDir", reporting.file("geb/chromeHeadless"))
        systemProperty("geb.env", "chromeHeadless")
    }

    register<Test>("acceptanceTestChrome") {
        description = "Runs the acceptance tests using Chrome."
        group = "verification"
        testClassesDirs = sourceSets["accTest"].output.classesDirs
        classpath = sourceSets["accTest"].runtimeClasspath

        outputs.upToDateWhen { false }

        systemProperty("geb.build.reportsDir", reporting.file("geb/chrome"))
        systemProperty("geb.env", "chrome")
    }

    register<Test>("acceptanceTestChromeHeadless") {
        description = "Runs the acceptance tests using headless Chrome."
        group = "verification"
        testClassesDirs = sourceSets["accTest"].output.classesDirs
        classpath = sourceSets["accTest"].runtimeClasspath

        outputs.upToDateWhen { false }

        systemProperty("geb.build.reportsDir", reporting.file("geb/chromeHeadless"))
        systemProperty("geb.env", "chromeHeadless")
    }

    register<Test>("integrationTest") {
        description = "Runs the integration tests."
        group = "verification"
        testClassesDirs = sourceSets["integTest"].output.classesDirs
        classpath = sourceSets["integTest"].runtimeClasspath
    }

    withType<KotlinCompile> {
        kotlinOptions {
            freeCompilerArgs = listOf("-Xjsr305=strict")
            jvmTarget = "1.8"
        }
    }
}

fun gitRev() = ProcessBuilder("git", "rev-parse", "HEAD").start().let { p ->
    p.waitFor(100, TimeUnit.MILLISECONDS)
    p.inputStream.bufferedReader().readLine() ?: "none"
}
