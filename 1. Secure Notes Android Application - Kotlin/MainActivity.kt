package com.example.cs50project

import androidx.appcompat.app.AppCompatActivity
import android.os.Bundle
import android.widget.Button
import android.widget.EditText
import android.content.Intent
import android.provider.Settings
import android.widget.TextView
import android.widget.Toast

class MainActivity : AppCompatActivity() {
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_main)

        //Call SQL datatbase
        var users = dbHelper(applicationContext)
        var db = users.readableDatabase

        // Create variables within page
        val btn_login = findViewById(R.id.buttonLogIn) as Button
        val btn_register = findViewById(R.id.buttonRegister) as Button

        // Move to Register Page
        btn_register.setOnClickListener {
            val register = Intent(this, Register::class.java)
            startActivity(register)
        }

            // Log in and move to index page
            btn_login.setOnClickListener {
                var etusername = findViewById(R.id.etUsername) as EditText
                var etpassword = findViewById(R.id.etPassword) as EditText


                var user = listOf<String>(etusername.text.toString(), etpassword.text.toString()).toTypedArray()
                var check = db.rawQuery("SELECT * FROM users WHERE Username = ? AND Password = ?", user)
                val username = etusername.text

                if (check.moveToNext()) {

                    val index = Intent(this, Index::class.java)
                        index.putExtra("Username", username.toString())
                        startActivity(index)


                } else {
                    Toast.makeText(applicationContext, "Invalid details", Toast.LENGTH_SHORT).show()
                }

            }


        }
    }
