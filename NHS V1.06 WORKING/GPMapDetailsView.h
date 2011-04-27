//
//  GPMapDetailsView.h
//  PushViewControllerAnimated
//
//  Created by Andrew  Farmer on 23/03/2010.
//  Copyright 2010 Kioty Ltd. All rights reserved.
//

#import <UIKit/UIKit.h>

NSString * informacion ;

@interface GPMapDetailsView : UIViewController {

	IBOutlet UILabel * labelNombre ;
	IBOutlet UILabel * labelPartner ;
	IBOutlet UILabel * labelManagerName ;
	IBOutlet UITextView * labelManagerMail ;
	IBOutlet UILabel * labelAddress1 ;
	IBOutlet UILabel * labelAddress2 ;
	
	IBOutlet UITextView * labelPhone ;
	
	IBOutlet UILabel * labelAddress1b ;
		
}


-(NSString *) dataFilePathGP ;

-(IBAction)makeAppointmentButton ;


@end
