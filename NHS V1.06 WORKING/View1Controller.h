//
//  View1Controller.h
//  PushViewControllerAnimated
//
//  Created by Andrew  Farmer on 12/02/2010.
//  Copyright 2010 Kioty Ltd. All rights reserved.
//

#import <UIKit/UIKit.h>
#import <MapKit/MapKit.h>

int valorB ;

@interface View1Controller : UIViewController<MKMapViewDelegate> {
	
	IBOutlet UIImageView * defaultImage ;
	IBOutlet UILabel * NHSBristolLabel ;
	IBOutlet UILabel * loadingLabel ;
	IBOutlet UIActivityIndicatorView * activity ;
	
	IBOutlet UIImageView * imageLoading ; 
	
	IBOutlet UIImageView * imageBar ;
	IBOutlet UIView * mainView ;
	IBOutlet UIView * viewBar ;
	
	IBOutlet UIView * barNHS ;
	
	IBOutlet UIImage * imageNHS ; 
	
	IBOutlet MKMapView * mapa ;
	
	NSTimer * myTimer;
	
	IBOutlet UIButton *  SOSBut ;
	IBOutlet UIButton *  ServicesBut ;
	IBOutlet UIButton *  NHSDirectBut;
	IBOutlet UIButton *  PersonalBut ;
	IBOutlet UIButton *  RemindersBut ;
	IBOutlet UIButton *  ContactNHSBut  ;	
	
	IBOutlet UIView * helpView ;
	IBOutlet UIView * loadingView ;
	
	IBOutlet UIButton * showMeDoctorHelpButton ;
	
	IBOutlet UILabel * tempoLabel ;
	
	IBOutlet UITextView * helpTexto ;
	//IBOutlet UIButton * buttonYes ;
	IBOutlet UIButton * closeHelpText ;
	IBOutlet UIBarButtonItem * closeViewText ;
	IBOutlet UIToolbar * toolbar ;
}

-(IBAction)StopSmokingButton:(id) sender;
-(IBAction)HelpMeButton:(id)sender;
-(IBAction)ServiceFinderButton:(id)sender;
-(IBAction)NHSDirectButton:(id)sender ;
-(IBAction)PersonalButton:(id)sender ;
-(IBAction)AppointmentsButton:(id)sender ;
-(IBAction)contactNHSView:(id)sender ;
-(IBAction)showMeDoctorHelpAction:(id)sender ;

-(IBAction)showMeDoctorHelpMeYES:(id)sender ;
-(IBAction)showMeDoctorHelpMeNO:(id)sender ;

-(IBAction)goHome:(id)sender ;


@end
