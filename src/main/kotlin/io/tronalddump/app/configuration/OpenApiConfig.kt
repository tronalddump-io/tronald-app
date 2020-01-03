package io.tronalddump.app.configuration

import io.swagger.v3.oas.models.Components
import io.swagger.v3.oas.models.OpenAPI
import io.swagger.v3.oas.models.info.Contact
import io.swagger.v3.oas.models.info.Info
import io.swagger.v3.oas.models.info.License
import io.swagger.v3.oas.models.servers.Server
import org.springframework.context.annotation.Bean
import org.springframework.context.annotation.Configuration

@Configuration
class OpenApiConfig {

    @Bean
    fun openApi(): OpenAPI {
        return OpenAPI()
                .components(Components())
                .info(Info()
                        .contact(Contact()
                                .email("m@matchilling.com")
                                .name("Mathias Schilling")
                        )
                        .description("Api & web archive for the dumbest things Donald Trump has ever said.")
                        .license(License()
                                .name("GNU General Public License v3.0")
                                .url("https://github.com/tronalddump-io/tronald-app/blob/master/LICENSE.md")
                        )
                        .title("io.tronalddump.api")
                        .version("1.0.0")
                )
                .servers(listOf(
                        Server()
                                .url("https://api.tronalddump.io")
                                .description("Production server (uses live data)")
                ))
    }
}