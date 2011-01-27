//
//  PharmacyMAPDetailsView.h
//  PushViewControllerAnimated
//
//  Created by Andrew  Farmer on 29/03/2010.
//  Copyright 2010 Kioty Ltd. All rights reserved.
//

#import <UIKit/UIKit.h>

NSString * informacion ;

@interface PharmacyMAPDetailsView : UIViewController {

	IBOutlet UILabel * labelNombre ;
	IBOutlet UILabel * labelPartner ;
	IBOutlet UILabel * labelManagerName ;
	IBOutlet UITextView * labelManagerMail ;
	IBOutlet UILabel * labelAddress1 ;
	IBOutlet UILabel * labelAddress2 ;
	
	IBOutlet UITextView * labelPhone ;
	
	IBOutlet UILabel * labelAddress1b ;

}



-(NSString *) dataFilePathPharmacy ;

-(IBAction)makeAppointmentButton ;
-(IBAction)sendMail:(id)sender ;

@end