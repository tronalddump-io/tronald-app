package io.tronalddump.app

import org.springframework.boot.test.context.SpringBootTest
import org.springframework.test.context.ActiveProfiles
import spock.lang.Specification

@ActiveProfiles("h2")
@SpringBootTest
abstract class BaseSpecification extends Specification {
}