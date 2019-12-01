import org.jetbrains.kotlin.gradle.tasks.KotlinCompile

plugins {
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

val sourceSets = the<SourceSetContainer>()

sourceSets {
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
    implementation("org.jetbrains.kotlin:kotlin-reflect")
    implementation("org.jetbrains.kotlin:kotlin-stdlib-jdk8")
    implementation("org.postgresql:postgresql:42.2.8")
    implementation("org.springframework.boot:spring-boot-starter-data-jpa")
    implementation("org.springframework.boot:spring-boot-starter-mustache")
    implementation("org.springframework.boot:spring-boot-starter-web")
    implementation("org.springframework.hateoas:spring-hateoas:1.0.1.RELEASE")

    runtimeOnly("com.h2database:h2")
    runtimeOnly("org.springframework.boot:spring-boot-devtools")

    testImplementation("org.springframework.boot:spring-boot-starter-test") {
        exclude(group = "org.junit.vintage", module = "junit-vintage-engine")
    }
    testImplementation("org.spockframework:spock-core:1.3-groovy-2.5")
    testImplementation("org.spockframework:spock-spring:1.3-groovy-2.5")
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

    named("check") {
        dependsOn("integrationTest")
    }

    register<Test>("integrationTest") {
        description = "Runs the integration tests."
        group = "verification"
        testClassesDirs = sourceSets["integTest"].output.classesDirs
        classpath = sourceSets["integTest"].runtimeClasspath

        mustRunAfter(test)
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
