import { StatusBar } from "expo-status-bar";
import { Button, StyleSheet, Text, View, Linking } from "react-native";
import React, { useState, useEffect } from "react";
import { Camera } from "expo-camera";
import * as Font from "expo-font";

export default function App() {
  const [hasPermission, setHasPermission] = useState(null);
  const [scanData, setScanData] = useState(null);
  const [screen, setScreen] = useState("options");
  const [apiLink, setApiLink] = useState(null);
  const [selectedOption, setSelectedOption] = useState("");
  const [fontLoaded, setFontLoaded] = useState(false);

  useEffect(() => {
    async function loadFont() {
      await Font.loadAsync({
        "montserrat-regular": require("./assets/fonts/Montserrat-Regular.ttf"),
        "montserrat-bold": require("./assets/fonts/Montserrat-Bold.ttf"),
      });
      setFontLoaded(true);
    }

    loadFont();
  }, []);

  useEffect(() => {
    (async () => {
      const { status } = await Camera.requestCameraPermissionsAsync();
      setHasPermission(status === "granted");
    })();
  }, []);

  const handleOptionSelected = (link, optionName) => {
    setApiLink(link);
    setSelectedOption(optionName);
    setScreen("scanner");
  };

  const handleFoodOptionSelected = (link, optionName) => {
    setApiLink(link);
    setSelectedOption(optionName);
    setScreen("scanner");
  };

  const handleBarCodeScanned = async ({ data }) => {
    setScanData(data);
    setScreen("result");
    console.log(`Participant with ${data} has been scanned!`);
  
    try {
      const formData = new FormData();
      formData.append("id", data);
      formData.append("event", selectedOption);
      formData.append("action", "hadfood")
  
      const response = await fetch(apiLink, {
        method: "POST",
        headers: {
          Accept: "application/json",
          "Content-Type": "multipart/form-data",
        },
        body: formData,
      });

      const responseData = await response.json();
      console.log("Response from API:", responseData);

      if (response.ok) {
        setScanData(responseData.information);
      } else {
        console.error("Error:", response.status);
        // Set scanData to null in case of an error
        setScanData(null);
      }
    } catch (error) {
      console.error("Error sending POST request:", error);
      // Set scanData to null in case of an error
      setScanData(null);
    }
  };

  const resetScan = () => {
    setScanData(null);
    setScreen("options");
    setSelectedOption("");
  };

  if (!fontLoaded || !hasPermission) {
    return null;
  }

  return (
    <View style={[styles.container, styles.darkBackground]}>
      {screen === "options" && (
        <View>
          <Text style={[styles.title, styles.fontMontserrat]}>
            Crescendo Attendance
          </Text>
          <View style={styles.options}>
            <Button
              title="Mech-a-thon"
              onPress={() =>
                handleOptionSelected(
                  "https://crescendo.hxrsh.tech/api/registrations",
                  "MECH-A-THON"
                )
              }
              style={styles.button}
            />
            <View style={styles.buttonMargin} />
            <Button
              title="Elex-a-thon"
              onPress={() =>
                handleOptionSelected(
                  "https://crescendo.hxrsh.tech/api/registrations",
                  "ELEX-A-THON"
                )
              }
              style={styles.button}
            />
            <View style={styles.buttonMargin} />
            <Button
              title="Hack-a-thon"
              onPress={() =>
                handleOptionSelected(
                  "https://crescendo.hxrsh.tech/api/registrations",
                  "HACK-A-THON"
                )
              }
              style={styles.button}
            />
            <View style={styles.buttonMargin} />
            <Button
              title="Food"
              onPress={() =>
                handleFoodOptionSelected(
                  "https://crescendo.hxrsh.tech/api/food",
                  "Food"
                )
              }
              style={styles.button}
            />
            <View style={styles.buttonMargin} />
          </View>
        </View>
      )}
      {screen === "scanner" && (
        <View style={styles.scanner}>
          <Text
            style={[
              styles.whiteText,
              styles.padding,
              styles.fontMontserrat,
              styles.scannerTitleText,
            ]}
          >
            Scanning for: {selectedOption}
          </Text>
          <Camera
            style={styles.cameraPreview}
            type={Camera.Constants.Type.back}
            onBarCodeScanned={handleBarCodeScanned}
          />
          <View style={styles.cancelButton}>
            <Button title="Cancel" onPress={resetScan} color="#007AFF" />
          </View>
        </View>
      )}
      {screen === "result" && (
        <View style={styles.result}>
          <Text
            style={[styles.whiteText, styles.fontMontserrat, styles.padding]}
          >
            {scanData ? (
              <>
                <Text>Scanned data:</Text>
                {"\n"}
                {"\n"}
                <Text>ID: {scanData.id}</Text>
                {"\n"}
                <Text>Name: {scanData.name}</Text>
                {"\n"}
                <Text>Event: {scanData.event}</Text>
                {"\n"}
                <Text>Food: {scanData.food}</Text>
              </>
            ) : (
              <Text>No data available</Text>
            )}
          </Text>
          <View style={styles.buttonMargin} />
          <Button title="Scan Again" onPress={resetScan} color="#2196f3" />
        </View>
      )}

      {screen === "options" && (
        <View style={styles.footer}>
          <Text style={[styles.footerText, styles.fontMontserrat]}>
            Developed and Designed by{" "}
            <Text
              style={[styles.link, styles.fontMontserrat]}
              onPress={() => Linking.openURL("https://github.com/imhxrsh")}
            >
              Harsh Vishwakarma
            </Text>
          </Text>
        </View>
      )}
      <StatusBar style="light" />
    </View>
  );
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
    alignItems: "center",
    justifyContent: "center",
  },
  darkBackground: {
    backgroundColor: "#333333",
  },
  whiteText: {
    alignContent: "center",
    justifyContent: "center",
    textAlign: "center",
    fontSize: 25,
    color: "#FFFFFF",
  },
  options: {
    flexDirection: "column",
    alignItems: "center",
    justifyContent: "center",
    marginTop: 20,
  },
  buttonMargin: {
    marginVertical: 10,
  },
  button: {
    marginVertical: 10,
  },
  scanner: {
    flex: 1,
    width: "100%",
    alignItems: "center",
    justifyContent: "center",
  },
  cameraPreview: {
    flex: 1,
    width: "155%",
  },
  cancelButton: {
    position: "absolute",
    bottom: 20,
  },
  result: {
    flex: 1,
    alignItems: "center",
    justifyContent: "center",
  },
  padding: {
    padding: 20,
  },
  title: {
    fontSize: 40,
    fontWeight: "800",
    color: "#FFFFFF",
    marginBottom: 50,
    alignContent: "center",
    textAlign: "center",
    justifyContent: "center",
  },
  footer: {
    position: "absolute",
    bottom: 20,
    width: "100%",
    alignItems: "center",
    justifyContent: "center",
  },
  footerText: {
    fontSize: 20,
    textAlign: "center",
    color: "#FFFFFF",
  },
  link: {
    color: "#007AFF",
  },
  fontMontserrat: {
    fontFamily: "montserrat-regular",
  },
  scannerTitleText: {
    paddingTop: 40,
  },
});
