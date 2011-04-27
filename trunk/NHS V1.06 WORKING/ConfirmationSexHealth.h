//
//  ConfirmationSexHealth.h
//  PushViewControllerAnimated
//
//  Created by Andrew Farmer on 03/09/2010.
//  Copyright 2010 __MyCompanyName__. All rights reserved.
//

#import <UIKit/UIKit.h>


@interface ConfirmationSexHealth : UIViewController {
	IBOutlet UIView * viewBar ;
	IBOutlet UIButton* webButton;
	BOOL avanzar ;
}

-(IBAction)goto4YPWebsite:(id)sender;
-(IBAction)continueButton:(id)sender ;
-(IBAction)goHome:(id)sender;


@end
