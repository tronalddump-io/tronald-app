package io.tronalddump.app.configuration;

import com.zaxxer.hikari.HikariDataSource;
import org.springframework.boot.jdbc.DataSourceBuilder;
import org.springframework.context.annotation.Bean;
import org.springframework.context.annotation.Configuration;
import org.springframework.data.jpa.repository.config.EnableJpaRepositories;
import org.springframework.transaction.annotation.EnableTransactionManagement;

import javax.sql.DataSource;

@Configuration
@EnableJpaRepositories(basePackages = "io.tronalddump")
@EnableTransactionManagement
public class DataSourceConfiguration {

  @Bean
  public DataSource dataSource(DataSourceProperties dataSourceProperties) {
    return DataSourceBuilder.create()
        .driverClassName(dataSourceProperties.getDriverClassName())
        .password(dataSourceProperties.getPassword())
        .type(HikariDataSource.class)
        .url(dataSourceProperties.getUrl())
        .username(dataSourceProperties.getUsername())
        .build();
  }
}
