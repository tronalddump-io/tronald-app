import org.openqa.selenium.chrome.ChromeDriver

/* See: http://www.gebish.org/manual/current/#configuration */
def env = System.getenv()

baseUrl = env['BASE_URL']
if (!baseUrl) {
    baseUrl = "http://localhost:8080"
}