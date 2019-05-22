import java.sql.*;
import org.mariadb.jdbc.Driver;

public class ProjeConnect {
    public static void main(String[] args) {
        try{
            String dbURL = "jdbc:mariadb://dijkstra.ug.bcc.bilkent.edu.tr/doga_oruc";
            String username = "doga.oruc";
            String password = "N64Bt4PV";
            Class.forName("org.mariadb.jdbc.Driver");
            Connection con=DriverManager.getConnection(dbURL,username,password);
            Statement stmt=con.createStatement();
            DatabaseMetaData dbm = con.getMetaData();
            deleteAll(dbm,stmt);



            String Address ="CREATE TABLE Address(" +
                    "Address_ID INTEGER(10) NOT NULL AUTO_INCREMENT," +
                    "City VARCHAR(255) NOT NULL," +
                    "Street_Name VARCHAR(255) NOT NULL," +
                    "Street_Address VARCHAR(255) NOT NULL," +
                    "PRIMARY KEY (Address_ID))"+
                    " ENGINE=InnoDB";
            String Court_case ="CREATE TABLE Court_Case (" +
                    "Case_ID INTEGER(10) NOT NULL AUTO_INCREMENT," +
                    "Case_Description VARCHAR(255) NOT NULL," +
                    "Case_file VARCHAR(255) NOT NULL," +
                    "IsClosed bit NOT NULL," +
                    "Result VARCHAR(255) NOT NULL," +
                    "Court_Name VARCHAR(255) NOT NULL," +
                    "Open_Date DATE NOT NULL,"+
                    "Case_State ENUM('Closed','On-Going'),"+
                    "Crime_ID INTEGER(10) NOT NULL," +
                    "PRIMARY KEY(Case_ID))ENGINE=InnoDB";
            String Crime ="CREATE TABLE Crime (" +
                    "Crime_ID INTEGER(10) NOT NULL AUTO_INCREMENT," +
                    "Date DATE NOT NULL," +
                    "Crime_Scene_Description VARCHAR(255) NOT NULL," +
                    "Crime_Description VARCHAR(255) NOT NULL," +
                    "Crime_Name VARCHAR(255) NOT NULL," +
                    "PRIMARY KEY(Crime_ID)" +
                    ")ENGINE=InnoDB";
            String Court ="CREATE TABLE Court(" +
                    "Court_Name VARCHAR(255) NOT NULL," +
                    "Address_ID INTEGER(10) NOT NULL," +
                    "Court_Type VARCHAR(32) NOT NULL," +
                    "PRIMARY KEY(Court_Name))ENGINE=InnoDB";
            String LanguageT ="CREATE TABLE Language(" +
                    "Language_Name VARCHAR(255) NOT NULL," +
                    "PRIMARY KEY(Language_Name)" +
                    ")ENGINE=InnoDB";
            String Citizen ="CREATE TABLE Citizen(" +
                    "TC_ID INTEGER(11) NOT NULL," +
                    "FullName VARCHAR(255) NOT NULL," +
                    "Birthdate DATE NOT NULL," +
                    "Biological_Sex BIT NOT NULL," +
                    "Nationality VARCHAR(255) NOT NULL," +
                    "Address_ID INTEGER(10)," +
                    "Citizen_Password VARCHAR(32) NOT NULL," +
                    "PRIMARY KEY(TC_ID)" +
                    ")ENGINE=InnoDB";

            String Judge ="CREATE TABLE Judge(" +
                    "TC_ID INTEGER(11) NOT NULL," +
                    "Gavel_Name VARCHAR(255) NOT NULL," +
                    "Judge_Password VARCHAR(32) NOT NULL," +
                    "PRIMARY KEY(TC_ID))ENGINE=InnoDB";
            String Litigant ="CREATE TABLE Litigant(" +
                    "TC_ID INTEGER(11) NOT NULL," +
                    "PRIMARY KEY(TC_ID)" +
                    ")ENGINE=InnoDB";
            String Conciliator ="CREATE TABLE Conciliator(" +
                    "TC_ID INTEGER(11) NOT NULL," +
                    "Conciliator_Password VARCHAR(32) NOT NULL," +
                    "PRIMARY KEY(TC_ID))ENGINE=InnoDB";

            String ExpertWitness ="CREATE TABLE Expert_Witness(" +
                    "TC_ID INTEGER(11) NOT NULL," +
                    "Expertise_Field VARCHAR(255) NOT NULL," +
                    "Expert_Password VARCHAR(32) NOT NULL," +
                    "PRIMARY KEY(TC_ID))ENGINE=InnoDB";
            String Lawyer ="CREATE TABLE Lawyer(" +
                    "TC_ID INTEGER(11) NOT NULL," +
                    "Office_Name VARCHAR(255) NOT NULL," +
                    "Bureau_Name VARCHAR(255) NOT NULL," +
                    "Lawyer_Password VARCHAR(32) NOT NULL," +
                    "PRIMARY KEY(TC_ID)" +
                    ")ENGINE=InnoDB";
            String Trial_Date ="CREATE TABLE Trial_Date (" +
                    "T_Date Date NOT NULL," +
                    " PRIMARY KEY(T_Date)" +
                    ")ENGINE=InnoDB";
            String Interpreter ="CREATE TABLE Interpreter(" +
                    "TC_ID INTEGER(11) NOT NULL," +
                    "Interpreter_Password VARCHAR(32) NOT NULL," +
                    "PRIMARY KEY(TC_ID)" +
                    ")ENGINE=InnoDB";
            String represents ="CREATE TABLE Represents (" +
                    "TC_ID INTEGER(11) NOT NULL," +
                    "Lawyer_ID INTEGER(10)," +
                    " PRIMARY KEY(Lawyer_ID, TC_ID)" +
                    ")ENGINE=InnoDB";
            String Councils = "CREATE TABLE Councils (" +
                    "Conciliator_ID INTEGER(11) NOT NULL," +
                    "Litigant_ID INTEGER(11) NOT NULL," +
                    "Case_ID INTEGER(10) NOT NULL," +
                    "Lawyer_ID INTEGER(11)," +
                    "Agreed BIT NOT NULL," +
                    " PRIMARY KEY(Conciliator_ID,Lawyer_ID, Litigant_ID, Case_ID)" +
                    ")ENGINE=InnoDB";
            String involved = "CREATE TABLE Involved (" +
                    "Lawyer_ID INTEGER(11)," +
                    "Litigant_ID INTEGER(11) NOT NULL," +
                    "Role VARCHAR(10) NOT NULL," +
                    "Case_ID INTEGER(11) NOT NULL," +
                    " PRIMARY KEY(Lawyer_ID,Litigant_ID, Case_ID)" +
                    ")ENGINE=InnoDB";
            String committed ="CREATE TABLE Committed_At (" +
                    "Address_ID INTEGER(10) NOT NULL," +
                    "Crime_ID INTEGER(10) NOT NULL," +
                    " PRIMARY KEY(Crime_Id, Address_Id)" +
                    ")ENGINE=InnoDB";
            String judges = "CREATE TABLE Judges (" +
                    "TC_ID INTEGER(11) NOT NULL," +
                    "Case_ID INTEGER(10) NOT NULL," +
                    " PRIMARY KEY(TC_ID, Case_ID)" +
                    ")ENGINE=InnoDB";
            String Testifies = "CREATE TABLE Informs (" +
                    "TC_ID INTEGER(11) NOT NULL," +
                    "Case_ID INTEGER(10) NOT NULL," +
                    " PRIMARY KEY(TC_ID, Case_ID)" +
                    ")ENGINE=InnoDB";
            String Works = "CREATE TABLE Works (" +
                    "TC_ID INTEGER(11) NOT NULL," +
                    "Case_ID INTEGER(10) NOT NULL," +
                    " PRIMARY KEY(TC_ID, Case_ID)" +
                    ")ENGINE=InnoDB";
            String Translates = "CREATE TABLE Translates (" +
                    "TC_ID INTEGER(11) NOT NULL," +
                    "Language_Name VARCHAR(255) NOT NULL," +
                    " PRIMARY KEY(TC_ID, Language_Name)" +
                    ")ENGINE=InnoDB";
            String TakesPlace = "CREATE TABLE TakesPlaceOn (" +
                    "Case_ID INTEGER(10) NOT NULL," +
                    "T_Date Date NOT NULL," +
                    " PRIMARY KEY(Case_ID, T_Date)" +
                    ")ENGINE=InnoDB";



            stmt.executeQuery(Address);
            stmt.executeQuery(Citizen);
            stmt.executeQuery(Litigant);
            stmt.executeQuery(Lawyer);
            stmt.executeQuery(Interpreter);
            stmt.executeQuery(ExpertWitness);
            stmt.executeQuery(Judge);
           stmt.executeQuery(Conciliator);
            stmt.executeQuery(LanguageT);
            stmt.executeQuery(Court);
            stmt.executeQuery(Court_case);
            stmt.executeQuery(Crime);
            stmt.executeQuery(represents);
            stmt.executeQuery(Councils);
            stmt.executeQuery(involved);
            stmt.executeQuery(Trial_Date);
            stmt.executeQuery(committed);
            stmt.executeQuery(judges);
            stmt.executeQuery(Testifies);
            stmt.executeQuery(Works);
            stmt.executeQuery(Translates);
            stmt.executeQuery(TakesPlace);

            stmt.executeQuery("ALTER TABLE Citizen ADD CONSTRAINT forKey1 FOREIGN KEY (Address_ID) REFERENCES Address(Address_ID);");
            stmt.executeQuery("ALTER TABLE Litigant ADD CONSTRAINT forKey2 FOREIGN KEY (TC_ID) REFERENCES Citizen(TC_ID);");
            stmt.executeQuery("ALTER TABLE Lawyer ADD CONSTRAINT forKey3 FOREIGN KEY (TC_ID) REFERENCES Citizen(TC_ID);");
            stmt.executeQuery("ALTER TABLE Expert_Witness ADD CONSTRAINT forKey4 FOREIGN KEY (TC_ID) REFERENCES Citizen(TC_ID);");
            stmt.executeQuery("ALTER TABLE Judge ADD CONSTRAINT forKey5 FOREIGN KEY (TC_ID) REFERENCES Citizen(TC_ID);");
            stmt.executeQuery("ALTER TABLE Conciliator ADD CONSTRAINT forKey6 FOREIGN KEY (TC_ID) REFERENCES Citizen(TC_ID);");
            stmt.executeQuery("ALTER TABLE Interpreter ADD CONSTRAINT forKey7 FOREIGN KEY (TC_ID) REFERENCES Citizen(TC_ID);");
            stmt.executeQuery("ALTER TABLE Court ADD CONSTRAINT forKey8 FOREIGN KEY (Address_ID) REFERENCES Address(Address_ID);");

            stmt.executeQuery("ALTER TABLE Court_Case ADD CONSTRAINT forKey10 FOREIGN KEY (Court_Name) REFERENCES Court(Court_Name);");
            stmt.executeQuery("ALTER TABLE Court_Case ADD CONSTRAINT forKey12 FOREIGN KEY (Crime_ID) REFERENCES Crime(Crime_ID);");
            stmt.executeQuery("ALTER TABLE Represents ADD CONSTRAINT forKey13 FOREIGN KEY (Lawyer_ID) REFERENCES Lawyer(TC_ID);");
            stmt.executeQuery("ALTER TABLE Represents ADD CONSTRAINT forKey14 FOREIGN KEY (TC_ID) REFERENCES Litigant(TC_ID);");
            stmt.executeQuery("ALTER TABLE Councils ADD CONSTRAINT forKey16 FOREIGN KEY (Conciliator_ID) REFERENCES Conciliator(TC_ID);");
            stmt.executeQuery("ALTER TABLE Councils ADD CONSTRAINT forKey17 FOREIGN KEY(Lawyer_ID,Litigant_ID) REFERENCES Represents(Lawyer_ID, TC_ID);");
            stmt.executeQuery("ALTER TABLE Councils ADD CONSTRAINT forKey18 FOREIGN KEY (Case_ID) REFERENCES Court_Case(Case_ID);");
            stmt.executeQuery("ALTER TABLE Involved ADD CONSTRAINT forKey22 FOREIGN KEY (Case_ID) REFERENCES Court_Case(Case_ID);");
            stmt.executeQuery("ALTER TABLE Involved ADD CONSTRAINT forKey24 FOREIGN KEY (Lawyer_ID,Litigant_ID) REFERENCES Represents(Lawyer_ID, TC_ID);");

            stmt.executeQuery("ALTER TABLE Committed_At ADD CONSTRAINT forKey25 FOREIGN KEY (Address_ID) REFERENCES Address(Address_ID);");
            stmt.executeQuery("ALTER TABLE Committed_At ADD CONSTRAINT forKey26 FOREIGN KEY (Crime_ID) REFERENCES Crime(Crime_ID);");
            stmt.executeQuery("ALTER TABLE Judges ADD CONSTRAINT forKey27 FOREIGN KEY (TC_ID) REFERENCES Judge(TC_ID);");
            stmt.executeQuery("ALTER TABLE Judges ADD CONSTRAINT forKey28 FOREIGN KEY (Case_ID) REFERENCES Court_Case(Case_ID);");
            stmt.executeQuery("ALTER TABLE Informs ADD CONSTRAINT forKey29 FOREIGN KEY (TC_ID) REFERENCES Expert_Witness(TC_ID);");
            stmt.executeQuery("ALTER TABLE Informs ADD CONSTRAINT forKey30 FOREIGN KEY (Case_ID) REFERENCES Court_Case(Case_ID);");
            stmt.executeQuery("ALTER TABLE Works ADD CONSTRAINT forKey261 FOREIGN KEY (TC_ID) REFERENCES Interpreter(TC_ID);");
            stmt.executeQuery("ALTER TABLE Works ADD CONSTRAINT forKey33 FOREIGN KEY (Case_ID) REFERENCES Court_Case(Case_ID);");
            stmt.executeQuery("ALTER TABLE Translates ADD CONSTRAINT forKey31 FOREIGN KEY (TC_ID) REFERENCES Interpreter(TC_ID);");
            stmt.executeQuery("ALTER TABLE Translates ADD CONSTRAINT forKey32 FOREIGN KEY (Language_Name) REFERENCES Language (Language_Name);");

            stmt.executeQuery("ALTER TABLE TakesPlaceOn ADD CONSTRAINT forKey9 FOREIGN KEY (Case_ID) REFERENCES Court_Case(Case_ID);");
            stmt.executeQuery("ALTER TABLE TakesPlaceOn ADD CONSTRAINT forKey34 FOREIGN KEY (T_Date) REFERENCES Trial_Date(T_Date);");
            addData(stmt);

        }catch(Exception e){
            System.out.println(e);
        }

    }
    public static void addData(Statement stmt) {
        try {
            stmt.executeQuery("INSERT INTO Address(City, Street_Name,Street_Address) VALUES('Corum', 'Osmancik', 'Osmancik Mah.');");
            stmt.executeQuery("INSERT INTO Address(City, Street_Name,Street_Address) VALUES('Corum', 'Osmancik', 'Hidirin evi');");
            stmt.executeQuery("INSERT INTO Address(City, Street_Name,Street_Address) VALUES('Corum', 'Osmancik', 'Bilal emminin evi');");
            stmt.executeQuery("INSERT INTO Address(City, Street_Name,Street_Address) VALUES('Corum', 'Osmancik', 'Sulonun cadiri');");
            stmt.executeQuery("INSERT INTO Address(City, Street_Name,Street_Address) VALUES('Corum', 'Osmancik', 'Hamzanin mekan');");
            stmt.executeQuery("INSERT INTO Address(City, Street_Name,Street_Address) VALUES('Ars', 'Yuksek yuksek tepeler', 'Uzak...');");
            stmt.executeQuery("INSERT INTO Address(City, Street_Name,Street_Address) VALUES('Corum', 'Osmancik', 'Meydaan');");
            stmt.executeQuery("INSERT INTO Address(City, Street_Name,Street_Address) VALUES('Corum', 'Osmancik', 'Gemi');");
            stmt.executeQuery("INSERT INTO Address(City, Street_Name,Street_Address) VALUES('Urfa', 'Merkez', 'Bizim ora');");

            stmt.executeQuery("INSERT INTO Court(Court_Name, Address_ID, Court_Type) VALUES('Truva Tribune',1,'Babayigit Mahkemesi');");
            stmt.executeQuery("INSERT INTO Crime(Date, Crime_Scene_Description, Crime_Description, Crime_Name) VALUES('2008-11-11','Ney ney, kalaba mi','Torpaklar icin gelmisler','Satiyi seekmisler');");
            stmt.executeQuery("INSERT INTO Language VALUES('Corumca');");
            stmt.executeQuery("INSERT INTO Language VALUES('Allah_lang');");
            stmt.executeQuery("INSERT INTO Language VALUES('Turkce');");
            stmt.executeQuery("INSERT INTO Citizen VALUES(00000000000, 'Bilal Emmi','1978-11-11',1,'Corumlu','0000000002','bilal_emmi');");
            stmt.executeQuery("INSERT INTO Citizen VALUES(00000000001, 'Hidir','1978-09-11',1,'Corumlu','0000000001','hidir');");
            stmt.executeQuery("INSERT INTO Citizen VALUES(00000000002, 'Sarilarin Sulo','1978-09-09',1,'Corumlu','0000000003','sulo');");
            stmt.executeQuery("INSERT INTO Citizen VALUES(00000000003, 'Hamzaa','1978-09-09',1,'Corumlu','0000000004','hamza');");
            stmt.executeQuery("INSERT INTO Citizen VALUES(00000000004, 'Allah','1970-01-01',1,'???','0000000005','eyv_kocm');");
            stmt.executeQuery("INSERT INTO Citizen VALUES(00000000005, 'Selaaaaddinin','1969-01-01',1,'Corumlu','0000000007','sati_nerde');");
            stmt.executeQuery("INSERT INTO Citizen VALUES(00000000006, 'Cim Bicme Makinesi','1969-03-01',1,'Corumlu','0000000007','virrn');");
            stmt.executeQuery("INSERT INTO Citizen VALUES(00000000007, 'Faruk','1969-04-01',1,'Corumlu','0000000008','gay');");


            stmt.executeQuery("INSERT INTO Judge VALUES(0000000004, 'Sopa (ama yok)', 'eyv_kocm')");
            stmt.executeQuery("INSERT INTO Lawyer VALUES(0000000001,'Truva','Osmancik Belediyespor','hidir');");
            stmt.executeQuery("INSERT INTO Lawyer VALUES(0000000000,'Truva','Osmancik Belediyespor','bilal_emmi');");
            stmt.executeQuery("INSERT INTO Litigant VALUES(0000000002);");
            stmt.executeQuery("INSERT INTO Litigant VALUES(0000000003);");

            stmt.executeQuery("INSERT INTO Court_Case(Case_Description, Case_file, IsClosed, Result, Court_Name, Open_Date, Case_State, Crime_ID) VALUES('Topraklarin alinmasi meselesi','burada surada', 0, '','Truva Tribune','2008-11-11','On-Going',0000000001);");

            stmt.executeQuery("INSERT INTO Represents VALUES(0000000002, 0000000000);");
            stmt.executeQuery("INSERT INTO Represents VALUES(0000000003, 0000000001);");

            stmt.executeQuery("INSERT INTO Involved VALUES(0000000000, 0000000002, 'Victim',0000000001);");
            stmt.executeQuery("INSERT INTO Involved VALUES(0000000001, 0000000003, 'Suspect', 0000000001);");
            stmt.executeQuery("INSERT INTO Committed_At VALUES(0000000006, 0000000001);");

            stmt.executeQuery("INSERT INTO Trial_Date VALUES('1970-01-02');");

            stmt.executeQuery("INSERT INTO Judges VALUES(0000000004, 0000000001);");
            stmt.executeQuery("INSERT INTO TakesPlaceOn VALUES(0000000001, '1970-01-02');");


            stmt.executeQuery("INSERT INTO Conciliator VALUES(0000000005, 'sati_nerde')");
            stmt.executeQuery("INSERT INTO Expert_Witness VALUES(0000000007, 'Bos yapmaktir', 'gay');");
            stmt.executeQuery("INSERT INTO Expert_Witness VALUES(0000000004, 'Literally everything', 'god');");
            stmt.executeQuery("INSERT INTO Interpreter VALUES(0000000006, 'virrn')");
            stmt.executeQuery("INSERT INTO Interpreter VALUES(0000000004, 'god')");
            stmt.executeQuery("INSERT INTO Translates VALUES(0000000006, 'Corumca')");
            stmt.executeQuery("INSERT INTO Translates VALUES(0000000004, 'Allah_lang')");
            //stmt.executeQuery("INSERT INTO Councils VALUES(0000000005, 0000000002, 0000000001, 0000000000, 0)");
            //stmt.executeQuery("INSERT INTO Councils VALUES(0000000005, 0000000003, 0000000001, 0000000001, 1)");
            //stmt.executeQuery("INSERT INTO Informs VALUES(0000000007, 0000000001)");
            //stmt.executeQuery("INSERT INTO Works VALUES(0000000006, 0000000001)");




        }
        catch(Exception e) {
            e.printStackTrace();
        }
    }

    public static void deleteAll(DatabaseMetaData dbm,Statement stmt){
        try {
            stmt.executeQuery("SET FOREIGN_KEY_CHECKS = 0;");
            stmt.executeQuery("DROP TABLE IF EXISTS Address;");
            stmt.executeQuery("DROP TABLE IF EXISTS Court_Case;");
            stmt.executeQuery("DROP TABLE IF EXISTS Crime;");
            stmt.executeQuery("DROP TABLE IF EXISTS Court;");
            stmt.executeQuery("DROP TABLE IF EXISTS Language;");
            stmt.executeQuery("DROP TABLE IF EXISTS Citizen;");
            stmt.executeQuery("DROP TABLE IF EXISTS Judge;");
            stmt.executeQuery("DROP TABLE IF EXISTS Litigant;");
            stmt.executeQuery("DROP TABLE IF EXISTS Conciliator;");
            stmt.executeQuery("DROP TABLE IF EXISTS Expert_Witness;");
            stmt.executeQuery("DROP TABLE IF EXISTS Trial_Date;");
            stmt.executeQuery("DROP TABLE IF EXISTS Interpreter;");
            stmt.executeQuery("DROP TABLE IF EXISTS Lawyer;");
            stmt.executeQuery("DROP TABLE IF EXISTS Represents;");
            stmt.executeQuery("DROP TABLE IF EXISTS Councils;");
            stmt.executeQuery("DROP TABLE IF EXISTS Involved;");
            stmt.executeQuery("DROP TABLE IF EXISTS Committed_At;");
            stmt.executeQuery("DROP TABLE IF EXISTS Judges;");
            stmt.executeQuery("DROP TABLE IF EXISTS Informs;");
            stmt.executeQuery("DROP TABLE IF EXISTS Works;");
            stmt.executeQuery("DROP TABLE IF EXISTS Translates;");
            stmt.executeQuery("DROP TABLE IF EXISTS TakesPlaceOn;");
            stmt.executeQuery("SET FOREIGN_KEY_CHECKS = 1;");

        }catch(Exception e){
            System.out.println(e);
        }
    }
}
