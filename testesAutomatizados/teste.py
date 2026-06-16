from selenium import webdriver
from selenium.webdriver.common.by import By
from selenium.webdriver.common.keys import Keys
from selenium.webdriver.firefox.service import Service
import time

driver = webdriver.Firefox(
    service=Service("/snap/bin/geckodriver")
)

driver.get("http://localhost/EnerSync/login.php")

email = driver.find_element(By.NAME, "email")
email.send_keys("francescovenancio@gmail.com")

senha = driver.find_element(By.NAME, "senha")
senha.send_keys("1234")

botao = driver.find_element(By.NAME, "botao")
botao.click()

time.sleep(2)

driver.quit()