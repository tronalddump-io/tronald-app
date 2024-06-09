package io.tronalddump.app.configuration;

import org.springframework.beans.factory.annotation.Value;
import org.springframework.boot.autoconfigure.condition.ConditionalOnProperty;
import org.springframework.context.annotation.Configuration;

@ConditionalOnProperty(prefix = "spring.datasource", name = "url")
@Configuration
public class DefaultDataSourceProperties implements DataSourceProperties {

  private final String driverClassName;
  private final String password;
  private final String url;
  private final String username;

  public DefaultDataSourceProperties(
      @Value("${spring.datasource.driver-class-name}") String driverClassName,
      @Value("${spring.datasource.password}") String password,
      @Value("${spring.datasource.url}") String url,
      @Value("${spring.datasource.username}") String username
  ) {
    this.driverClassName = driverClassName;
    this.password = password;
    this.url = url;
    this.username = username;
  }

  @Override
  public String getDriverClassName() {
    return this.driverClassName;
  }

  @Override
  public String getPassword() {
    return this.password;
  }

  @Override
  public String getUrl() {
    return this.url;
  }

  @Override
  public String getUsername() {
    return this.username;
  }
}