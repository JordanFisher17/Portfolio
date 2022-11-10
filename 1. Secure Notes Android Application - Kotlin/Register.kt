package com.example.cs50project

import android.content.ContentValues
import android.content.Context
import android.content.Intent
import androidx.appcompat.app.AppCompatActivity
import android.os.Bundle
import android.widget.Button
import android.widget.EditText
import android.widget.TextView
import android.widget.Toast
import androidx.core.content.contentValuesOf

class Register : AppCompatActivity() {
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_register)

        var users = dbHelper(applicationContext)
        var db = users.writableDatabase

        //Program back button
        val btn_back = findViewById(R.id.btn_back) as Button
        btn_back.setOnClickListener {
            val main = Intent(this, MainActivity::class.java)
            startActivity(main)
        }
        //Program register button and add user to database
        var btn_create = findViewById(R.id.btnCreate) as Button
        btn_create.setOnClickListener {
            var username = findViewById(R.id.etRegisterUsername) as EditText
            var password = findViewById(R.id.etnewPassword) as EditText
            var passwordCon = findViewById(R.id.etConfirmPassword) as EditText
            var notes = ""

            //Check that details are entered and that these are correct

            if (username.text.toString() == "" || password.text.toString() == "") {
                Toast.makeText(this, "Invalid Details", Toast.LENGTH_SHORT).show()
            } else {

                //Check that username is not already taken
                val user = arrayOf(username.text.toString())
                val check = db.rawQuery("SELECT * FROM users WHERE Username = ?", user)


                if (check.moveToNext()) {
                    Toast.makeText(applicationContext, "Username already taken", Toast.LENGTH_SHORT)
                        .show()
                } else {


                    if (password.text.toString() != passwordCon.text.toString()) {
                        Toast.makeText(
                            applicationContext,
                            "Passwords do not match",
                            Toast.LENGTH_SHORT
                        )
                            .show()
                    } else {
                        users.AddUser(
                            username.text.toString(),
                            password.text.toString(),
                            notes.toString()
                        )

                        username.setText("")
                        password.setText("")
                        passwordCon.setText("")

                        val main = Intent(this, MainActivity::class.java)
                        startActivity(main)
                    }
                }
            }
        }
        }
    }
