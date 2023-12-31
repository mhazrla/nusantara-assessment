import React from "react";
import axios from "axios";

const API_URL = "http://localhost:8080/api/auth/";
const authService = () => {
  const login = (username, password) => {
    return axios
      .post(API_URL + "signin", {
        username,
        password,
      })
      .then((response) => {
        if (response.data.accessToken) {
          localStorage.setItem("user", JSON.stringify(response.data));
        }

        return response.data;
      });
  };

  const logout = () => {
    localStorage.removeItem("user");
  };

  const register = (username, email, password) => {
    return axios.post(API_URL + "signup", {
      username,
      email,
      password,
    });
  };

  const getCurrentUser = () => {
    return JSON.parse(localStorage.getItem("user"));
  };
};

export default authService;
