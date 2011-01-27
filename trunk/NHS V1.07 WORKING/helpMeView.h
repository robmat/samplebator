//
//  helpMeView.h
//  PushViewControllerAnimated
//
//  Created by Andrew  Farmer on 22/02/2010.
//  Copyright 2010 Kioty Ltd. All rights reserved.
//

#import <UIKit/UIKit.h>


@interface helpMeView : UIViewController{


	IBOutlet UIView * view1;
	IBOutlet UIView * view2 ;
	
}


//@property (retain,nonatomic) viewTestView ;

-(IBAction)TestButton:(id)sender ;
-(IBAction)goBackButton:(id)sender ;

-(IBAction)SOSPhoneButton:(id)sender ;
-(IBAction)SOSSMSButton:(id)sender ;
-(IBAction)SOSMailButton:(id)sender ;

@end
